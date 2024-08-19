<?php

declare(strict_types = 1);

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

namespace OpenConext\Profile\Value;

final readonly class Application
{
    public function __construct(
        private string $landingPage,
        private string $nameEn,
        private string $nameNl,
        private string $organisationEn,
        private string $organisationNl,
        private string $logo
    ) {}

    public function hasName(): bool
    {
        return $this->nameEn !== '' || $this->nameNl !== '';
    }

    public function hasOrganisationName(): bool
    {
        return $this->organisationEn !== '' || $this->organisationNl !== '';
    }

    public function hasLogo(): bool
    {
        return $this->logo !== null;
    }

    public function hasLandingPage(): bool
    {
        return $this->landingPage !== null;
    }

    public function getName(string $locale): string
    {
        switch ($locale) {
            case 'nl':
                return $this->nameNl;
            default:
                return $this->nameEn;
        }
    }

    public function getOrganisationName(string $locale): string
    {
        switch ($locale) {
            case 'nl':
                return $this->organisationNl;
            default:
                return $this->organisationEn;
        }
    }

    public function getLogo(): string
    {
        return $this->logo;
    }

    public function getLandingPage(): string
    {
        return $this->landingPage;
    }
}
