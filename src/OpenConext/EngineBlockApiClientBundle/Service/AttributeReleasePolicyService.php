<?php

/**
 * Copyright 2016 SURFnet B.V.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OpenConext\EngineBlockApiClientBundle\Service;

use OpenConext\EngineBlockApiClientBundle\Exception\InvalidResponseException;
use OpenConext\EngineBlockApiClientBundle\Http\JsonApiClient;
use OpenConext\Profile\Value\Consent;
use OpenConext\Profile\Value\ConsentList;
use OpenConext\Profile\Value\SpecifiedConsent;
use OpenConext\Profile\Value\SpecifiedConsentList;
use OpenConext\ProfileBundle\Attribute\AttributeSetWithFallbacks;
use stdClass;
use Surfnet\SamlBundle\Exception\UnknownUrnException;
use Surfnet\SamlBundle\SAML2\Attribute\Attribute;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDefinition;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDictionary;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSetInterface;

final class AttributeReleasePolicyService
{
    /**
     * @var JsonApiClient
     */
    private $jsonApiClient;

    /**
     * @var AttributeDictionary
     */
    private $attributeDictionary;

    public function __construct(JsonApiClient $jsonApiClient, AttributeDictionary $attributeDictionary)
    {
        $this->jsonApiClient = $jsonApiClient;
        $this->attributeDictionary = $attributeDictionary;
    }

    /**
     * @param ConsentList $consentList
     * @param AttributeSetInterface $attributeSet
     * @return SpecifiedConsentList
     * @SuppressWarnings(PHPMD.NPathComplexity) Build and mapping logic causes complexity
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects) Build and mapping logic causes complexity
     */
    public function applyAttributeReleasePolicies(ConsentList $consentList, AttributeSetInterface $attributeSet)
    {
        $entityIds = $consentList->map(function (Consent $consent) {
            return $consent->getServiceProvider()->getEntity()->getEntityId()->getEntityId();
        });

        $mappedAttributes = [];
        foreach ($attributeSet as $attribute) {
            $mace = $attribute->getAttributeDefinition()->getUrnMace();
            $oid  = $attribute->getAttributeDefinition()->getUrnOid();

            if ($mace !== null) {
                $mappedAttributes[$mace] = $attribute->getValue();
            }

            if ($oid !== null) {
                $mappedAttributes[$oid] = $attribute->getValue();
            }
        }

        $data = [
            'entityIds'  => $entityIds,
            'attributes' => !empty($mappedAttributes) ? $mappedAttributes : new stdClass()
        ];
        $response = $this->jsonApiClient->post($data, '/arp');

        $specifiedConsents = $consentList->map(
            function (Consent $consent) use ($response) {
                $entityId = $consent->getServiceProvider()->getEntity()->getEntityId()->getEntityId();

                if (!isset($response[$entityId])) {
                    throw new InvalidResponseException(
                        sprintf(
                            'EntityID "%s" was not found in the ARP response (entityIDs: %s)',
                            $entityId,
                            join(', ', array_keys($response))
                        )
                    );
                }

                $attributes = [];
                foreach ($response[$entityId] as $attributeName => $attributeValue) {
                    try {
                        $attributeDefinition = $this->attributeDictionary->getAttributeDefinitionByUrn($attributeName);
                    } catch (UnknownUrnException $exception) {
                        $attributeDefinition = new AttributeDefinition($attributeName, $attributeName, $attributeName);
                    }

                    $attribute = new Attribute($attributeDefinition, $attributeValue);
                    if (!in_array($attribute, $attributes)) {
                        $attributes[] = $attribute;
                    }
                }

                return SpecifiedConsent::specifies($consent, AttributeSetWithFallbacks::create($attributes));
            }
        );

        return SpecifiedConsentList::createWith($specifiedConsents);
    }
}
