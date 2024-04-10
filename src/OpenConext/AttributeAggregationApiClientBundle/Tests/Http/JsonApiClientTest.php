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

namespace OpenConext\AttributeAggregationApiClientBundle\Tests\Http;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Utils;
use OpenConext\AttributeAggregationApiClientBundle\Exception\InvalidResponseException;
use OpenConext\AttributeAggregationApiClientBundle\Exception\MalformedResponseException;
use OpenConext\AttributeAggregationApiClientBundle\Http\JsonApiClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use OpenConext\AttributeAggregationApiClientBundle\Exception\ResourceNotFoundException;

class JsonApiClientTest extends TestCase
{
    /**
     * @test
     * @group eb_api_service
     */
    public function throw_exception_when_json_cannot_be_parsed(): void
    {
        $this->expectException(MalformedResponseException::class);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn(Utils::streamFor('invalid json'));

        $guzzle = $this->createMock(ClientInterface::class);
        $guzzle->expects($this->once())
            ->method('request')
            ->with('GET', '/resource', $this->anything())
            ->willReturn($response);
        $service = new JsonApiClient($guzzle);
        $service->read('/resource');
    }

    /**
     * @test
     * @group eb_api_service
     */
    public function throw_exception_when_resource_cannot_be_found(): void
    {
        $this->expectException(ResourceNotFoundException::class);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(404);

        $guzzle = $this->createMock(ClientInterface::class);
        $guzzle->expects($this->once())
            ->method('request')
            ->with('GET', '/resource', $this->anything())
            ->willReturn($response);

        $service = new JsonApiClient($guzzle);
        $service->read('/resource');
    }

    /**
     * @test
     * @dataProvider notAllowedStatusCodeProvider
     * @group eb_api_service
     */
    public function throw_exception_when_status_code_is_not_200($statusCode): void
    {
        $this->expectException(InvalidResponseException::class);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn($statusCode);

        $guzzle = $this->createMock(ClientInterface::class);
        $guzzle->expects($this->once())
            ->method('request')
            ->with('GET', '/resource', $this->anything())
            ->willReturn($response);

        $service = new JsonApiClient($guzzle);
        $service->read('/resource');
    }

    /**
     * @test
     * @group eb_api_service
     */
    public function throw_exception_when_empty_resource(): void
    {
        $this->expectException('\RuntimeException');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);

        $guzzle = $this->createMock(ClientInterface::class);
        $guzzle->expects($this->never())
            ->method('request')
            ->with('GET', '', $this->anything())
            ->willReturn($response);

        $service = new JsonApiClient($guzzle);
        $service->read('');
    }

    /**
     * @test
     * @group eb_api_service
     */
    public function format_resource_parameters(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn(Utils::streamFor('{}'));

        $guzzle = $this->createMock(ClientInterface::class);
        $guzzle->expects($this->once())
            ->method('request')
            ->with('GET', '/resource/John%2FDoe', $this->anything())
            ->willReturn($response);

        $service = new JsonApiClient($guzzle);
        $service->read('/resource/%s', ['John/Doe']);
    }

    /**
     * @test
     * @group eb_api_service
     */
    public function pass_request_to_guzzle(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn(Utils::streamFor('{}'));

        $guzzle = $this->createMock(ClientInterface::class);
        $guzzle->expects($this->once())
            ->method('request')
            ->with('GET', '/resource', $this->anything())
            ->willReturn($response);

        $api = new JsonApiClient($guzzle);
        $api->read("/resource");
    }

    public function notAllowedStatusCodeProvider(): \Generator
    {
        yield [300];
        yield [400];
        yield [500];
    }
}
