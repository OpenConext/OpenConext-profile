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

use OpenConext\Profile\Assert;
use OpenConext\Profile\Exception\LogicException;

class DisplayName
{
    /**
     * @var string[]
     */
    private $translations = [];

    public function __construct(array $translations = [])
    {
        Assert::allString(array_keys($translations), 'Translations must be indexed by locale');
        Assert::allNotBlank(array_keys($translations), 'Locales may not be blank');
        Assert::allString($translations, 'Translations must be strings');

        $this->translations = $translations;
    }

    /**
     * @param string $locale
     * @return bool
     */
    public function hasFilledTranslationForLocale($locale)
    {
        Assert::string($locale, 'Locale must be string', 'locale');

        return array_key_exists($locale, $this->translations) && trim($this->translations[$locale]) !== '';
    }

    /**
     * @return string
     */
    public function getTranslation($locale)
    {
        if (!isset($this->translations[$locale])) {
            throw new LogicException(sprintf('Could not find translation for locale "%s"', $locale));
        }

        return $this->translations[$locale];
    }

    public function __toString()
    {
        return sprintf(
            'DisplayName(%s)',
            join(
                ', ',
                array_map(
                    function ($locale) {
                        return sprintf('%s=%s', $locale, $this->translations[$locale]);
                    },
                    array_keys($this->translations)
                )
            )
        );
    }
}
