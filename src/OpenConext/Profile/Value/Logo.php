<?php

/**
 * Copyright 2021 SURFnet B.V.
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

use Assert\AssertionFailedException;
use OpenConext\Profile\Assert;

final class Logo
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $height;

    /**
     * @var string
     */
    private $width;

    /**
     * @throws AssertionFailedException
     */
    public function __construct(string $url, string $height, string $width)
    {
        Assert::notBlank($url, 'Logo url cannot be blank');
        /**
         * Note: we are not testing the values of height/width here because:
         * - we are not using them at this time, so we don't care
         * - we don't know whether the api always returns them
         * Both properties are included for completenes' sake.
         */

        $this->url = $url;
        $this->height = $height;
        $this->width = $width;
    }

    /**
     * @throws AssertionFailedException
     */
    public static function fromArray(array $logo): Logo
    {
        return new self($logo['url'], $logo['height'], $logo['width']);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getHeight(): string
    {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getWidth(): string
    {
        return $this->width;
    }
}
