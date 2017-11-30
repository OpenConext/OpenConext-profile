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
                    $identifier = $enabledAttribute->getIdentifier();
                    if ($attributeAggregationAttributes->hasAttribute($identifier)) {
                        $aaAttribute = $attributeAggregationAttributes->getAttribute($identifier);
                        $collection[] = AttributeAggregationAttribute::fromConfig(
                            $enabledAttribute,
                            true,
                            $aaAttribute->getValues(),
                            $aaAttribute->getSource()
                        );
                    } else {
                        $collection[] = AttributeAggregationAttribute::fromConfig($enabledAttribute, false);
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
}
