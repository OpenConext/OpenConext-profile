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

use Exception;
use OpenConext\EngineBlockApiClientBundle\Http\JsonApiClient;
use OpenConext\EngineBlockApiClientBundle\Value\ConsentListFactory;
use OpenConext\Profile\Assert;
use OpenConext\Profile\Repository\ConsentRepositoryInterface;
use OpenConext\Profile\Value\ConsentList;
use Psr\Log\LoggerInterface;

final readonly class ConsentRepository implements ConsentRepositoryInterface
{
    public function __construct(private JsonApiClient $apiClient, private LoggerInterface $logger)
    {
    }

    public function findAllFor(string $userId): ConsentList
    {
        Assert::string($userId, 'User ID "%s" expected to be string, type %s given.');
        Assert::notEmpty($userId, 'User ID "%s" is empty, but non empty value was expected.');

        $consentListJson = $this->apiClient->read('consent/%s', [$userId]);

        return ConsentListFactory::createListFromMetadata($consentListJson);
    }

    public function deleteServiceWith(string $collabPersonId, string $entityId): bool
    {
        try {
            $this->logger->notice('Calling EngineBlock API remove-consent endpoint');
            $this->apiClient->post(
                ['collabPersonId' => $collabPersonId, 'serviceProviderEntityId' => $entityId],
                'remove-consent',
            );
            return true;
        } catch (Exception $e) {
            $this->logger->notice(sprintf('EngineBlock API returned a non 200 response with error message (%s)', $e->getMessage()));
            return false;
        }
    }
}
