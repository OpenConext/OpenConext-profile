<?php

declare(strict_types = 1);

/**
 * Copyright 2018 SURFnet B.V.
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

namespace OpenConext\UserLifecycleApiClientBundle\Http;

use OpenConext\UserLifecycleApiClientBundle\Exception\InvalidResponseException;
use OpenConext\UserLifecycleApiClientBundle\Exception\MalformedResponseException;
use OpenConext\UserLifecycleApiClientBundle\Exception\ResourceNotFoundException;
use OpenConext\UserLifecycleApiClientBundle\Exception\RuntimeException;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JsonApiClient
{
    public function __construct(
        private readonly HttpClientInterface $userLifecycleApiClient,
    ) {
    }

    /**
     * A URL path, optionally containing printf parameters. The parameters
     * will be URL encoded and formatted into the path string.
     * Example: "connections/%d.json"
     */
    public function read(
        string $path,
        array $parameters = [],
    ): array {
        $resource = $this->buildResourcePath($path, $parameters);

        $response = $this->userLifecycleApiClient->request('GET', $resource);

        $statusCode = $response->getStatusCode();

        if ($statusCode === 404) {
            throw new ResourceNotFoundException(sprintf('Resource "%s" not found', $resource));
        }

        if ($statusCode !== 200) {
            throw new InvalidResponseException(
                sprintf(
                    'Request to resource "%s" returned an invalid response with status code %s',
                    $resource,
                    $statusCode,
                ),
            );
        }

        try {
            $data = $response->toArray();
        } catch (DecodingExceptionInterface) {
            throw new MalformedResponseException(
                sprintf('Cannot read resource "%s": malformed JSON returned', $resource),
            );
        }

        return $data;
    }

    /**
     * @throws RuntimeException
     */
    private function buildResourcePath(
        string $path,
        array $parameters,
    ): string {
        if (count($parameters) > 0) {
            $resource = vsprintf($path, array_map('urlencode', $parameters));
        } else {
            $resource = $path;
        }

        if (empty($resource)) {
            throw new RuntimeException(
                sprintf(
                    'Could not construct resource path from path "%s", parameters "%s"',
                    $path,
                    implode('","', $parameters),
                ),
            );
        }

        return $resource;
    }
}
