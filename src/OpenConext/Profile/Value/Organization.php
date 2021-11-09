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

final class Organization
{
    /**
     * @var string[]
     */
    private $displayName;

    /**
     * @var string[]
     */
    private $name;

    /**
     * @var Logo
     */
    private $logo;

    public function __construct(array $displayName, array $name, Logo $logo)
    {
        Assert::allString(array_keys($displayName), 'DisplayName translations must be indexed by locale');
        Assert::allNotBlank(array_keys($displayName), 'Locales may not be blank');
        Assert::allString(array_keys($name), 'Name translations must be indexed by locale');
        Assert::allNotBlank(array_keys($name), 'Locales may not be blank');

        $this->displayName = $displayName;
        $this->name = $name;
        $this->logo = $logo;
    }

    public static function fromArray(array $json): Organization
    {
        Assert::keysArePresent($json, ['display_name', 'name', 'logo']);
        return new self($json['display_name'], $json['name'], Logo::fromArray($json['logo']));
    }

    public function getDisplayName(string $locale): string
    {
        $displayNameLocale = $this->displayName[$locale];
        if (!empty($displayNameLocale)) {
            return $displayNameLocale;
        }

        $localeName = $this->name[$locale];
        if (!empty($localeName)) {
            return $localeName;
        }

        $englishName = $this->name['en'];
        if (!empty($englishName)) {
            return $englishName;
        }

        return '';
    }

    public function getLogo(): Logo
    {
        return $this->logo;
    }
}
