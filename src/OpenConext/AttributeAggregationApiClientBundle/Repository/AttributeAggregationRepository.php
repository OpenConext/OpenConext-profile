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

namespace OpenConext\AttributeAggregationApiClientBundle\Repository;

use OpenConext\AttributeAggregationApiClientBundle\Http\JsonApiClient;
use OpenConext\Profile\Assert;
use OpenConext\Profile\Repository\AttributeAggregationRepository as AttributeAggregationRepositoryInterface;
use OpenConext\Profile\Value\AttributeAggregation\AttributeAggregationAttributesList;

final class AttributeAggregationRepository implements AttributeAggregationRepositoryInterface
{
    /**
     * @var JsonApiClient
     */
    private $apiClient;

    public function __construct(JsonApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function findAllFor($userId)
    {
        Assert::string($userId, '$userId "%s" (NameID) expected to be string, type %s given.');
        Assert::notEmpty($userId, '$userId "%s" (NameID) can not be empty');

        $attributes = $this->apiClient->read('accounts/%s', [$userId]);

        return AttributeAggregationAttributesList::fromApiResponse($attributes);
    }

    public function unsubscribeAccount($accountId)
    {
        Assert::integer($accountId, 'Account id "%s" expected to be string, type %s given.');

        $result = $this->apiClient->delete('disconnect/%d', [$accountId]);

        return isset($result->status) && $result->status === 'OK';
    }
}
