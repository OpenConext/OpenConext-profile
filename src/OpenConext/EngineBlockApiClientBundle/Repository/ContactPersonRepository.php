<?php

/**
 * Copyright 2015 SURFnet B.V.
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

use OpenConext\EngineBlockApiClientBundle\Http\JsonApiClient;
use OpenConext\Profile\Value\ContactPersonList;
use OpenConext\EngineBlockApiClientBundle\Value\ContactPersonListFactory;
use OpenConext\Profile\Repository\ContactPersonRepository as ContactPersonRepositoryInterface;
use OpenConext\Profile\Value\EntityId;

final class ContactPersonRepository implements ContactPersonRepositoryInterface
{
    /**
     * @var JsonApiClient
     */
    private $apiClient;

    public function __construct(JsonApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @param EntityId $entityId
     * @return ContactPersonList
     */
    public function findAllForIdp(EntityId $entityId)
    {
        $identityProviderJson = $this->apiClient->read('metadata/idp?entity-id=%s', [$entityId->getEntityId()]);

        return ContactPersonListFactory::createListFromMetadata($identityProviderJson);
    }
}
