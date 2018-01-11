<?php

/**
 * Copyright 2017 SURFnet B.V.
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

namespace OpenConext\ProfileBundle\Service;

use Exception;
use OpenConext\Profile\Entity\AuthenticatedUser;
use OpenConext\Profile\Repository\AttributeAggregationRepository;
use OpenConext\Profile\Value\AttributeAggregation\AttributeAggregationAttribute;
use OpenConext\Profile\Value\AttributeAggregation\AttributeAggregationAttributesList;
use OpenConext\Profile\Value\AttributeAggregation\AttributeAggregationEnabledAttributes;
use OpenConext\Profile\Value\SurfConextId;
use Psr\Log\LoggerInterface;
use Surfnet\SamlBundle\Exception\RuntimeException;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDefinition;

final class AttributeAggregationService
{
    /**
     * @var AttributeDefinition
     */
    private $surfConextUserIdAttributeDefinition;

    /**
     * @var AttributeAggregationRepository
     */
    private $repository;

    /**
     * @var AttributeAggregationEnabledAttributes
     */
    private $attributeAggregationEnabledAttributes;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        AttributeAggregationRepository $repository,
        AttributeDefinition $surfConextUserIdAttributeDefinition,
        AttributeAggregationEnabledAttributes $attributeAggregationEnabledAttributes,
        LoggerInterface $logger
    ) {
        $this->repository = $repository;
        $this->surfConextUserIdAttributeDefinition = $surfConextUserIdAttributeDefinition;
        $this->attributeAggregationEnabledAttributes = $attributeAggregationEnabledAttributes;
        $this->logger = $logger;
    }

    /**
     * @param AuthenticatedUser $user
     * @return null|AttributeAggregationAttributesList
     */
    public function findByUser(AuthenticatedUser $user)
    {
        $enabledAttributes = $this->attributeAggregationEnabledAttributes;

        try {
            $definition = $this->surfConextUserIdAttributeDefinition;
            $userAttributes = $user->getAttributes();
            // Does the logged in user have the SurfConextUserId attribute?
            if ($userAttributes->containsAttributeDefinedBy($definition)) {
                $collection = [];

                $samlAttribute = $userAttributes->getAttributeByDefinition($definition);
                $surfConextIdValue = $samlAttribute->getValue()[0];
                $surfConextId = new SurfConextId($surfConextIdValue);
                $attributeAggregationAttributes = $this->repository->findAllFor($surfConextId);

                foreach ($enabledAttributes->getAttributes() as $enabledAttribute) {
                    $accountType = $enabledAttribute->getAccountType();
                    if ($attributeAggregationAttributes->hasAttribute($accountType)) {
                        $aaAttribute = $attributeAggregationAttributes->getAttribute($accountType);
                        $collection[] = AttributeAggregationAttribute::fromConfig(
                            $enabledAttribute,
                            true,
                            $aaAttribute->getId(),
                            $aaAttribute->getSurfconextId(),
                            $aaAttribute->getLinkedId()
                        );
                    } else {
                        $collection[] = AttributeAggregationAttribute::fromConfig($enabledAttribute, false, -1, '');
                    }
                }

                return new AttributeAggregationAttributesList($collection);
            }
        } catch (Exception $e) {
            $this->logger->error(
                sprintf(
                    'Error while finding AA attributes. Original error message: "%s"',
                    $e->getMessage()
                )
            );
            return null;
        }

        $this->logger->notice('No enabled attribute aggregation attributes found.');
        return null;
    }

    /**
     * @param AuthenticatedUser $user
     * @param AttributeAggregationAttribute $orcidAttribute
     *
     * @return bool returns false when deletion failed
     */
    public function disconnectAttributeFor(AuthenticatedUser $user, AttributeAggregationAttribute $orcidAttribute)
    {
        if ($this->isValidRequest($user, $orcidAttribute)) {
            $result = $this->repository->unsubscribeAccount($orcidAttribute->getId());
            if (!$result) {
                $this->logger->error('Error while unsubscribing the AA attribute for the authenticating user.');
            }
            return $result;
        }
        return false;
    }

    /**
     * Validate the users identity matches that of the identity set on the ORCiD attribute retrieved from AA.
     *
     * @param AttributeAggregationAttribute $orcidAttribute
     *
     * @return bool
     */
    private function isValidRequest(AuthenticatedUser $user, AttributeAggregationAttribute $orcidAttribute)
    {
        try {
            $surfConextId = $user->getAttributes()->getAttributeByDefinition(
                new AttributeDefinition('surfconextId', null, 'urn:oid:1.3.6.1.4.1.1076.20.40.40.1')
            );
        } catch (RuntimeException $e) {
            $this->logger->error('Attempted to find authenticated users surfconextId but was unable to find it.');
            return false;
        }

        if ($surfConextId->getValue()[0] !== $orcidAttribute->getSurfconextId()) {
            $this->logger->error(
                'The surfconextId associated with ORCiD ID account does not match the surfconextId of the 
                authenticated user.'
            );
            return false;
        }

        return true;
    }
}
