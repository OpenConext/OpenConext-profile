<?php

declare(strict_types = 1);

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

namespace OpenConext\EngineBlockApiClient\Repository;

use Exception;
use OpenConext\EngineBlockApiClient\Exception\ResourceNotFoundException;
use OpenConext\EngineBlockApiClient\Http\JsonApiClient;
use OpenConext\Profile\Entity\AuthenticatedUser;
use OpenConext\Profile\Value\EntityId;
use OpenConext\Profile\Value\Organization;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class InstitutionRepository
{
    public function __construct(
        private JsonApiClient $apiClient,
        private EntityId $engineBlockEntityId,
    ) {
    }

    private function findAllForIdp(
        string $entityId,
    ) {
        try {
            return $this->apiClient->read('metadata/idp?entity-id=%s', [$entityId]);
        } catch (Exception $e) {
            throw new ResourceNotFoundException(
                sprintf('EngineBlock API returned a non 200 response with error message (%s)', $e->getMessage()),
            );
        }
    }

    /**
     * @param EntityId[] $entityIds
     */
    private function getNearestAuthenticatingAuthorityEntityId(
        array $entityIds,
    ): ?EntityId {
        $lastEntityId = array_pop($entityIds);

        if ($lastEntityId === null) {
            return null;
        }

        if (!$this->engineBlockEntityId->equals($lastEntityId)) {
            return $lastEntityId;
        }

        return array_pop($entityIds);
    }

    public function getOrganizationAndLogoForIdp(
        UserInterface $user,
    ): Organization {
        assert($user instanceof AuthenticatedUser);

        $entityIds = $user->getAuthenticatingAuthorities();
        $authenticatingIdpEntityId = $this->getNearestAuthenticatingAuthorityEntityId($entityIds);
        $json = $this->findAllForIdp($authenticatingIdpEntityId->getEntityId());

        return Organization::fromArray($json);
    }
}
