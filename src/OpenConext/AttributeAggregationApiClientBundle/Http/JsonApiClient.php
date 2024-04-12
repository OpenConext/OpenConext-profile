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

namespace OpenConext\AttributeAggregationApiClientBundle\Http;

use GuzzleHttp\ClientInterface;
use OpenConext\AttributeAggregationApiClientBundle\Exception\InvalidArgumentException;
use OpenConext\AttributeAggregationApiClientBundle\Exception\InvalidResponseException;
use OpenConext\AttributeAggregationApiClientBundle\Exception\MalformedResponseException;
use OpenConext\AttributeAggregationApiClientBundle\Exception\ResourceNotFoundException;
use OpenConext\AttributeAggregationApiClientBundle\Exception\RuntimeException;

class JsonApiClient
{
    public function __construct(
        private readonly ClientInterface $httpClient,
    ) {
    }

    /**
     * @param string $path A URL path, optionally containing printf parameters. The parameters
     *               will be URL encoded and formatted into the path string.
     *               Example: "connections/%d.json"
     * @return mixed $data
     * @throws InvalidResponseException
     * @throws MalformedResponseException
     * @throws ResourceNotFoundException
     */
    public function read(string $path, array $parameters = []): mixed
    {
        return $this->handle('GET', $path, $parameters);
    }

    /**
     * @param string $path A URL path, optionally containing printf parameters. The parameters
     *               will be URL encoded and formatted into the path string.
     *               Example: "connections/%d.json"
     * @return mixed $data
     * @throws InvalidResponseException
     * @throws MalformedResponseException
     * @throws ResourceNotFoundException
     */
    public function delete(string $path, array $parameters = []): mixed
    {
        return $this->handle('DELETE', $path, $parameters);
    }

    private function handle(string $method, string $path, array $parameters = [])
    {
        $resource = $this->buildResourcePath($path, $parameters);

        $response = $this->httpClient->request($method, $resource, ['exceptions' => false]);

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
            $data = $this->parseJson((string) $response->getBody());
        } catch (InvalidArgumentException) {
            throw new MalformedResponseException(
                sprintf('Cannot read resource "%s": malformed JSON returned', $resource),
            );
        }

        return $data;
    }


    private function buildResourcePath(string $path, array $parameters): string
    {
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

    /**
     * Function to provide functionality common to Guzzle 5 Response's json method,
     * without config options as they are not needed.
     */
    private function parseJson(string $json): mixed
    {
        $data = json_decode($json, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException('Unable to parse JSON data: ' . json_last_error_msg());
        }

        return $data;
    }
}
