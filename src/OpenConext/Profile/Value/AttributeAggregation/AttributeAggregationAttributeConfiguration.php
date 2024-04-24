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

namespace OpenConext\Profile\Value\AttributeAggregation;

final class AttributeAggregationAttributeConfiguration
{
    public function __construct(
        private readonly string $accountType,
        private readonly string $logoPath,
        private readonly string $connectUrl,
    ) {
    }

    public static function fromConfig(
        $accountType,
        array $attributeConfigParameters,
    ): self {
        return new self(
            $accountType,
            $attributeConfigParameters['logo_path'],
            $attributeConfigParameters['connect_url'],
        );
    }

    public function getAccountType(): string
    {
        return $this->accountType;
    }

    public function getLogoPath(): string
    {
        return $this->logoPath;
    }

    public function getConnectUrl(): string
    {
        return $this->connectUrl;
    }
}
