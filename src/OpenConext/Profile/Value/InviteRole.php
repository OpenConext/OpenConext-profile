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
    /**
     * @var Application[]
     */
    private array $applications;

    /**
     * @param array<int, array<string, mixed>> $applications
     */
    public function __construct(
        private string $name,
        private string $description,
        array $applications,
    ) {
        $this->applications = array_values(array_map(
            fn(array $appData) => new Application(
                self::requireOptionalString($appData['landingPage'] ?? null, 'landingPage'),
                self::requireOptionalString($appData['nameEn'] ?? null, 'nameEn'),
                self::requireOptionalString($appData['nameNl'] ?? null, 'nameNl'),
                self::requireOptionalString($appData['organisationEn'] ?? null, 'organisationEn'),
                self::requireOptionalString($appData['organisationNl'] ?? null, 'organisationNl'),
                self::requireOptionalString($appData['logo'] ?? null, 'logo'),
            ),
            $applications,
        ));
    }

    private static function requireOptionalString(mixed $value, string $field): string
    {
        if ($value === null) {
            return '';
        }

        if (!is_string($value)) {
            throw new \TypeError(sprintf('Invite role application field "%s" must be a string', $field));
        }

        return $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function hasApplications(): bool
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
