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

final readonly class InviteRole
{
    private array $applications;

    public function __construct(
        private string $name,
        private string $description,
        array $applications
    ) {
        $this->applications = array_map(
            fn(array $appData) => new Application(
                $appData['landingPage'] ?? '',
                $appData['nameEn'] ?? '',
                $appData['nameNl'] ?? '',
                $appData['organisationEn'] ?? '',
                $appData['organisationNl'] ?? '',
                $appData['logo'] ?? ''
            ),
            $applications
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function hasApplications()
    {
        return count($this->applications) > 0;
    }

    /**
     * @return array<int, Application>
     */
    public function getApplications(): array
    {
        return $this->applications;
    }
}
