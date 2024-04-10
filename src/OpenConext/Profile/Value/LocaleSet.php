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

use ArrayIterator;
use Countable;
use IteratorAggregate;

final class LocaleSet implements IteratorAggregate, Countable
{
    /**
     * @var Locale[]
     */
    private $locales = [];

    /**
     * @param Locale[] $locales
     * @return LocaleSet
     */
    public static function create(array $locales)
    {
        $localeSet = new LocaleSet();

        foreach ($locales as $locale) {
            $localeSet->initializeWith($locale);
        }

        return $localeSet;
    }

    private function __construct()
    {
    }

    /**
     * @return bool
     */
    public function contains(Locale $otherLocale): bool
    {
        foreach ($this->locales as $locale) {
            if ($locale->equals($otherLocale)) {
                return true;
            }
        }

        return false;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->locales);
    }

    public function count(): int
    {
        return count($this->locales);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod) PHPMD does not see that this is being called in our static method
     */
    private function initializeWith(Locale $locale): void
    {
        if ($this->contains($locale)) {
            return;
        }

        $this->locales[] = $locale;
    }
}
