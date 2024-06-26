<?php

declare(strict_types = 1);

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

namespace OpenConext\EngineBlockApiClient\Repository;

use OpenConext\EngineBlockApiClient\Http\JsonApiClient;
use OpenConext\EngineBlockApiClient\Value\ContactPersonListFactory;
use OpenConext\Profile\Repository\ContactPersonRepositoryInterface;
use OpenConext\Profile\Value\ContactPersonList;
use OpenConext\Profile\Value\EntityId;

final readonly class ContactPersonRepository implements ContactPersonRepositoryInterface
{
    public function __construct(
        private JsonApiClient $apiClient,
    ) {
    }

    public function findAllForIdp(
        EntityId $entityId,
    ): ContactPersonList {
        $identityProviderJson = $this->apiClient->read('metadata/idp?entity-id=%s', [$entityId->getEntityId()]);

        return ContactPersonListFactory::createListFromMetadata($identityProviderJson);
    }
}
