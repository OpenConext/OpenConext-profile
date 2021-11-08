<?php

/**
 * Copyright 2021 SURFnet B.V.
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

namespace OpenConext\EngineBlockApiClientBundle\Repository;

use Exception;
use OpenConext\EngineBlockApiClientBundle\Exception\ResourceNotFoundException;
use OpenConext\EngineBlockApiClientBundle\Http\JsonApiClient;
use OpenConext\Profile\Entity\AuthenticatedUser;
use OpenConext\Profile\Value\EntityId;
use OpenConext\Profile\Value\Organization;
use Psr\Log\LoggerInterface;

final class InstitutionRepository
{
    /**
     * @var JsonApiClient
     */
    private $apiClient;

    /**
     * @var EntityId
     */
    private $engineBlockEntityId;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        JsonApiClient $apiClient,
        EntityId $engineBlockEntityId,
        LoggerInterface $logger
    ) {
        $this->apiClient = $apiClient;
        $this->engineBlockEntityId = $engineBlockEntityId;
        $this->logger = $logger;
    }

    private function findAllForIdp(string $entityId)
    {
        try {
            return $this->apiClient->read('metadata/idp?entity-id=%s', [$entityId]);
        } catch (Exception $e) {
            $this->logger->notice(
                sprintf('EngineBlock API returned a non 200 response with error message (%s)', $e->getMessage())
            );

            throw new ResourceNotFoundException();
        }
    }

    /**
     * @param EntityId[] $entityIds
     * @return null|EntityId
     */
    private function getNearestAuthenticatingAuthorityEntityId(array $entityIds)
    {
        $lastEntityId = array_pop($entityIds);

        if ($lastEntityId === null) {
            return null;
        }

        if (!$this->engineBlockEntityId->equals($lastEntityId)) {
            return $lastEntityId;
        }

        return array_pop($entityIds);
    }

    public function getOrganizationAndLogoForIdp(AuthenticatedUser $user): Organization
    {
        $entityIds = $user->getAuthenticatingAuthorities();
        $authenticatingIdpEntityId = $this->getNearestAuthenticatingAuthorityEntityId($entityIds);
        $json = $this->findAllForIdp($authenticatingIdpEntityId->getEntityId());

        return Organization::fromArray($json);
    }
}
