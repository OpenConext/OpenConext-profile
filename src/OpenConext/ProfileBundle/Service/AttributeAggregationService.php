<?php

declare(strict_types = 1);

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
use OpenConext\Profile\Repository\AttributeAggregationRepositoryInterface;
use OpenConext\Profile\Value\AttributeAggregation\AttributeAggregationAttribute;
use OpenConext\Profile\Value\AttributeAggregation\AttributeAggregationAttributesList;
use OpenConext\Profile\Value\AttributeAggregation\AttributeAggregationEnabledAttributes;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class AttributeAggregationService
{
    public function __construct(
        private readonly AttributeAggregationRepositoryInterface $repository,
        private AttributeAggregationEnabledAttributes $attributeAggregationEnabledAttributes,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function findByUser(
        UserInterface $user,
    ): ?AttributeAggregationAttributesList {
        $enabledAttributes = $this->attributeAggregationEnabledAttributes;

        assert($user instanceof AuthenticatedUser);

        try {
            $collection = [];

            $attributeAggregationAttributes = $this->repository->findAllFor($user->getNameId());

            foreach ($enabledAttributes->getAttributes() as $enabledAttribute) {
                $accountType = $enabledAttribute->getAccountType();
                if ($attributeAggregationAttributes->hasAttribute($accountType)) {
                    $aaAttribute = $attributeAggregationAttributes->getAttribute($accountType);
                    $collection[] = AttributeAggregationAttribute::fromConfig(
                        $enabledAttribute,
                        true,
                        $aaAttribute->getId(),
                        $aaAttribute->getUserNameId(),
                        $aaAttribute->getLinkedId(),
                    );
                } else {
                    $collection[] = AttributeAggregationAttribute::fromConfig($enabledAttribute, false, -1, '');
                }
            }

            return new AttributeAggregationAttributesList($collection);
        } catch (Exception $e) {
            $this->logger->error(
                sprintf(
                    'Error while finding AA attributes. Original error message: "%s"',
                    $e->getMessage(),
                ),
            );
        }

        $this->logger->notice('No enabled attribute aggregation attributes found.');
        return null;
    }

    /**
     *
     * @return bool returns false when deletion failed
     */
    public function disconnectAttributeFor(
        UserInterface $user,
        AttributeAggregationAttribute $orcidAttribute,
    ): bool {
        assert($user instanceof AuthenticatedUser);

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
     * Validate the users identity matches that of the identity set on the ORCID attribute retrieved from AA.
     */
    private function isValidRequest(
        AuthenticatedUser $user,
        AttributeAggregationAttribute $orcidAttribute,
    ): bool {
        $nameId = $user->getNameId();

        if ($nameId !== $orcidAttribute->getUserNameId()) {
            $this->logger->error(
                'The users NameId associated with ORCID iD account does not match the NameId of the 
                authenticated user.',
            );
            return false;
        }

        return true;
    }
}
