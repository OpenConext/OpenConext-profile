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

use OpenConext\EngineBlockApiClientBundle\Http\JsonApiClient;
use OpenConext\Profile\Value\Consent;
use OpenConext\Profile\Value\ConsentList;
use OpenConext\Profile\Value\SpecifiedConsent;
use OpenConext\Profile\Value\SpecifiedConsentList;
use OpenConext\ProfileBundle\Attribute\AttributeSetWithFallbacks;
use Surfnet\SamlBundle\SAML2\Attribute\Attribute;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSetInterface;

final class AttributeReleasePolicyService
{
    /**
     * @var JsonApiClient
     */
    private $jsonApiClient;

    public function __construct(JsonApiClient $jsonApiClient)
    {
        $this->jsonApiClient = $jsonApiClient;
    }

    /**
     * @param ConsentList $consentList
     * @param AttributeSetInterface $attributeSet
     * @return SpecifiedConsentList
     */
    public function applyAttributeReleasePolicies(ConsentList $consentList, AttributeSetInterface $attributeSet)
    {
        $entityIds = $consentList->map(function (Consent $consent) {
            return $consent->getServiceProvider()->getEntity()->getEntityId()->getEntityId();
        });

        $mappedAttributes = [];
        $definitions = [];
        foreach ($attributeSet as $attribute) {
            $name = $attribute->getAttributeDefinition()->getUrnMace();

            if ($name === null) {
                $name = $attribute->getAttributeDefinition()->getUrnOid();
            }

            $mappedAttributes[$name] = $attribute->getValue();

            // Remember the attribute definitions so we can more easily build the attributes again
            $definitions[$name] = $attribute->getAttributeDefinition();
        }

        $response = $this->jsonApiClient->post('/arp', [
            'entityIds'  => $entityIds,
            'attributes' => $mappedAttributes
        ]);

        return SpecifiedConsentList::createWith(
            $consentList->map(
                function (Consent $consent) use ($response, $definitions) {
                    $entityId = $consent->getServiceProvider()->getEntity()->getEntityId()->getEntityId();

                    $attributes = [];
                    foreach ($response[$entityId] as $attributeName => $attributeValue) {
                        $attributes[] = new Attribute($definitions[$attributeName], $attributeValue);
                    }

                    return SpecifiedConsent::specifies($consent, AttributeSetWithFallbacks::create($attributes));
                }
            )
        );
    }
}
