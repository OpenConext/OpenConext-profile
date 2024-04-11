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

namespace OpenConext\AttributeAggregationApiClientBundle\Repository;

use Assert\AssertionFailedException;
use OpenConext\AttributeAggregationApiClientBundle\Http\JsonApiClient;
use OpenConext\Profile\Assert;
use OpenConext\Profile\Repository\AttributeAggregationRepositoryInterface;
use OpenConext\Profile\Value\AttributeAggregation\AttributeAggregationAttributesList;

final readonly class AttributeAggregationRepository implements AttributeAggregationRepositoryInterface
{
    public function __construct(
        private JsonApiClient $apiClient,
    ) {
    }

    /**
     * @throws AssertionFailedException
     */
    public function findAllFor(string $userId): AttributeAggregationAttributesList
    {
        Assert::notEmpty($userId, '$userId "%s" (NameID) can not be empty');

        $attributes = $this->apiClient->read('accounts/%s', [$userId]);

        return AttributeAggregationAttributesList::fromApiResponse($attributes);
    }

    public function unsubscribeAccount(int $accountId): bool
    {
        $result = $this->apiClient->delete('disconnect/%d', [$accountId]);

        return isset($result->status) && $result->status === 'OK';
    }
}
