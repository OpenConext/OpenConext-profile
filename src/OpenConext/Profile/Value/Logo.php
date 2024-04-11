<?php

declare(strict_types = 1);

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

use OpenConext\Profile\Assert;

final class Logo
{
    private readonly string $url;

    private readonly string $height;

    private readonly string $width;

    public function __construct(?string $url, ?string $height, ?string $width)
    {
        $this->url = $url ?? '';
        $this->height = $height ?? '';
        $this->width = $width ?? '';
    }

    public static function fromArray(array $logo): Logo
    {
        Assert::keysArePresent($logo, ['url', 'height', 'width']);
        return new self($logo['url'], $logo['height'], $logo['width']);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function hasUrl(): bool
    {
        return !empty($this->getUrl());
    }
}
