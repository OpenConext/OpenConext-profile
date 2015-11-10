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

namespace OpenConext\EngineBlockApiClientBundle\Http;

use GuzzleHttp\ClientInterface;
use OpenConext\EngineBlockApiClientBundle\Exception\InvalidArgumentException;
use OpenConext\EngineBlockApiClientBundle\Exception\InvalidResponseException;
use OpenConext\EngineBlockApiClientBundle\Exception\MalformedResponseException;
use OpenConext\EngineBlockApiClientBundle\Exception\ResourceNotFoundException;
use OpenConext\EngineBlockApiClientBundle\Exception\RuntimeException;

class JsonApiClient
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @param ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $path A URL path, optionally containing printf parameters. The parameters
     *               will be URL encoded and formatted into the path string.
     *               Example: "connections/%d.json"
     * @param array  $parameters
     * @return mixed $data
     * @throws InvalidResponseException
     * @throws MalformedResponseException
     * @throws ResourceNotFoundException
     */
    public function read($path, array $parameters = [])
    {
        $resource = $this->buildResourcePath($path, $parameters);

        $response = $this->httpClient->request('GET', $resource, ['exceptions' => false]);

        $statusCode = $response->getStatusCode();

        if ($statusCode === 404) {
            throw new ResourceNotFoundException(sprintf('Resource "%s" not found', $resource));
        }

        if ($statusCode !== 200) {
            throw new InvalidResponseException(
                sprintf(
                    'Request to resource "%s" returned an invalid response with status code %s',
                    $resource,
                    $statusCode
                )
            );
        }

        try {
            $data = $this->parseJson((string) $response->getBody());
        } catch (InvalidArgumentException $e) {
            throw new MalformedResponseException(
                sprintf('Cannot read resource "%s": malformed JSON returned', $resource)
            );
        }

        return $data;
    }

    /**
     * @param       $path
     * @param array $parameters
     * @return string
     * @throws RuntimeException
     */
    private function buildResourcePath($path, array $parameters)
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
                    implode('","', $parameters)
                )
            );
        }

        return $resource;
    }

    /**
     * Function to provide functionality common to Guzzle 5 Response's json method,
     * without config options as they are not needed.
     *
     * @param string $json
     * @return mixed
     * @throws InvalidArgumentException
     */
    private function parseJson($json)
    {
        static $jsonErrors = [
            JSON_ERROR_DEPTH          => 'JSON_ERROR_DEPTH - Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => 'JSON_ERROR_STATE_MISMATCH - Underflow or the modes mismatch',
            JSON_ERROR_CTRL_CHAR      => 'JSON_ERROR_CTRL_CHAR - Unexpected control character found',
            JSON_ERROR_SYNTAX         => 'JSON_ERROR_SYNTAX - Syntax error, malformed JSON',
            JSON_ERROR_UTF8           => 'JSON_ERROR_UTF8 - Malformed UTF-8 characters, possibly incorrectly encoded',
        ];

        $data = json_decode($json, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            $last = json_last_error();

            $errorMessage = $jsonErrors[$last];

            if (!isset($errorMessage)) {
                $errorMessage = 'Unknown error';
            }

            throw new InvalidArgumentException(sprintf('Unable to parse JSON data: %s', $errorMessage));
        }

        return $data;
    }
}
