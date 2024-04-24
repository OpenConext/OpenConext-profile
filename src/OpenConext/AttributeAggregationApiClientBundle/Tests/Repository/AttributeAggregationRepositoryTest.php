<?php

declare(strict_types=1);

/**
 * Copyright 2024 SURFnet B.V.
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

namespace OpenConext\AttributeAggregationApiClientBundle\Tests\Repository;

use OpenConext\AttributeAggregationApiClientBundle\Http\JsonApiClient;
use OpenConext\AttributeAggregationApiClientBundle\Repository\AttributeAggregationRepository;
use OpenConext\Profile\Value\AttributeAggregation\AttributeAggregationAttributesList;
use PHPUnit\Framework\TestCase;

class AttributeAggregationRepositoryTest extends TestCase

{
    public function testFindAllForReturnsAttributesList(): void
    {
        $attribute1 = [
            'id' => 1,
            'urn' => 'urn:collab:person:example.com:admin',
            'accountType' => 'admin',
            'linkedId' => 'string',
        ];

        $attribute2 = [
            'id' => 2,
            'urn' => 'urn:collab:person:example.com:user',
            'accountType' => 'user',
            'linkedId' => 'string',
        ];

        $apiClient = $this->createMock(JsonApiClient::class);
        $apiClient->method('read')
            ->with('accounts/%s', ['userId'])
            ->willReturn([$attribute1, $attribute2]);

        $repository = new AttributeAggregationRepository($apiClient);

        $result = $repository->findAllFor('userId');

        $this->assertInstanceOf(AttributeAggregationAttributesList::class, $result);
    }

    public function testFindAllForThrowsExceptionForEmptyUserId(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $apiClient = $this->createMock(JsonApiClient::class);
        $repository = new AttributeAggregationRepository($apiClient);

        $repository->findAllFor('');
    }

    public function testUnsubscribeAccountReturnsTrueForSuccessfulDeletion(): void
    {
        $apiClient = $this->createMock(JsonApiClient::class);
        $apiClient->method('delete')
            ->with('disconnect/%d', [1])
            ->willReturn(['status' => 'OK']);

        $repository = new AttributeAggregationRepository($apiClient);

        $result = $repository->unsubscribeAccount(1);

        $this->assertTrue($result);
    }

    public function testUnsubscribeAccountReturnsFalseForFailedDeletion(): void
    {
        $apiClient = $this->createMock(JsonApiClient::class);
        $apiClient->method('delete')
            ->with('disconnect/%d', [1])
            ->willReturn(['status' => 'ERROR']);

        $repository = new AttributeAggregationRepository($apiClient);

        $result = $repository->unsubscribeAccount(1);

        $this->assertFalse($result);
    }

    public function testUnsubscribeAccountReturnsFalseForException(): void
    {
        $apiClient = $this->createMock(JsonApiClient::class);
        $apiClient->method('delete')
            ->willThrowException(new \Exception());

        $repository = new AttributeAggregationRepository($apiClient);

        $result = $repository->unsubscribeAccount(1);

        $this->assertFalse($result);
    }
}
