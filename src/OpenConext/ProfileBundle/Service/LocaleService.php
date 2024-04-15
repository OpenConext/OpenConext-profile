<?php

declare(strict_types = 1);

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

namespace OpenConext\ProfileBundle\Service;

use OpenConext\Profile\Api\ApiUserInterface;
use OpenConext\Profile\Repository\LocaleRepositoryInterface;
use OpenConext\Profile\Value\Locale;
use OpenConext\Profile\Value\LocaleSet;

final readonly class LocaleService
{
    public function __construct(
        private LocaleRepositoryInterface $localeRepository,
        private LocaleSet $availableLocales,
        private Locale $defaultLocale,
    ) {
    }

    public function isAvailableLocale(
        Locale $locale,
    ): bool {
        return $this->availableLocales->contains($locale);
    }

    public function getLocale(): Locale
    {
        $locale = $this->localeRepository->findLocale();

        if ($locale) {
            return $locale;
        }

        return $this->defaultLocale;
    }

    public function saveLocaleOf(
        ApiUserInterface $user,
    ): void {
        $this->localeRepository->save($user->getLocale());
    }

    public function getAvailableLocales(): LocaleSet
    {
        return $this->availableLocales;
    }
}
