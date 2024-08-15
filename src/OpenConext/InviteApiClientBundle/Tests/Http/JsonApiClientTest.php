<?php

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

namespace OpenConext\InviteApiClientBundle\Tests\Http;


use Generator;
use OpenConext\InviteApiClientBundle\Exception\InvalidResponseException;
use OpenConext\InviteApiClientBundle\Exception\ProfileNotFoundException;
use OpenConext\InviteApiClientBundle\Exception\MalformedResponseException;
use OpenConext\InviteApiClientBundle\Http\JsonApiClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

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
        $response->method('toArray')->willThrowException(new JsonException());

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with('GET', '/profile', $this->anything())
            ->willReturn($response);

        $service = new JsonApiClient($httpClient);
        $service->read('/profile');
    }

    /**
     * @test
     * @group eb_api_service
     */
    public function throw_exception_when_resource_cannot_be_found(): void
    {
        $this->expectException(ProfileNotFoundException::class);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(404);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with('GET', '/profile', $this->anything())
            ->willReturn($response);

        $service = new JsonApiClient($httpClient);
        $service->read('/profile');
    }

    /**
     * @test
     * @dataProvider notAllowedStatusCodeProvider
     * @group eb_api_service
     */
    public function throw_exception_when_status_code_is_not_200(int $statusCode): void
    {
        $this->expectException(InvalidResponseException::class);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn($statusCode);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with('GET', '/profile', $this->anything())
            ->willReturn($response);

        $service = new JsonApiClient($httpClient);
        $service->read('/profile');
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

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->never())
            ->method('request')
            ->with('GET', '', $this->anything())
            ->willReturn($response);

        $service = new JsonApiClient($httpClient);
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
        $response->method('toArray')->willReturn([]);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with('GET', '/profile/John%2FDoe', $this->anything())
            ->willReturn($response);

        $service = new JsonApiClient($httpClient);
        $service->read('/profile/%s', ['John/Doe']);
    }

    /**
     * @test
     * @group eb_api_service
     */
    public function pass_request_to_httpclient(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('toArray')->willReturn([]);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with('GET', '/profile', $this->anything())
            ->willReturn($response);

        $api = new JsonApiClient($httpClient);
        $api->read("/profile");
    }

    public function notAllowedStatusCodeProvider(): Generator
    {
        yield [300];
        yield [400];
        yield [500];
    }
}
