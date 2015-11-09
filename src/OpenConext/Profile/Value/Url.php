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

namespace OpenConext\Profile\Value;

use InvalidArgumentException;
use OpenConext\Profile\Assert;
use OpenConext\Profile\Exception\LogicException;

final class Url
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var (string|int|null)[]
     */
    private $parts;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        Assert::string($url, 'URL must be string');

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException('URL must be valid');
        }

        $parts = parse_url($url);

        if ($parts === false) {
            throw new LogicException('URL is valid, yet cannot be decomposed into its constituent parts');
        }

        $this->url = $url;
        $this->parts = $parts + [
            'scheme'   => null,
            'host'     => null,
            'port'     => null,
            'user'     => null,
            'pass'     => null,
            'path'     => null,
            'query'    => null,
            'fragment' => null,
        ];
    }

    /**
     * @param string $scheme
     * @return bool
     */
    public function hasScheme($scheme)
    {
        Assert::string($scheme);

        return strtolower($this->parts['scheme']) === strtolower($scheme);
    }

    /**
     * @param Url $other
     * @return bool
     */
    public function equals(Url $other)
    {
        return $this == $other;
    }

    public function __toString()
    {
        return $this->url;
    }
}
