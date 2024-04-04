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

namespace OpenConext\ProfileBundle\Service;

use OpenConext\Profile\Repository\LocaleRepositoryInterface;
use OpenConext\Profile\Value\Locale;
use OpenConext\Profile\Api\ApiUserInterface;
use OpenConext\Profile\Value\LocaleSet;

final class LocaleService
{
    /**
     * @var LocaleRepositoryInterface
     */
    private $localeRepository;

    /**
     * @var LocaleSet
     */
    private $availableLocales;

    /**
     * @var Locale
     */
    private $defaultLocale;

    /**
     * @param LocaleRepositoryInterface $localeRepository
     * @param LocaleSet $availableLocales
     * @param Locale $defaultLocale
     */
    public function __construct(
        LocaleRepositoryInterface $localeRepository,
        LocaleSet $availableLocales,
        Locale $defaultLocale,
    ) {
        $this->localeRepository = $localeRepository;
        $this->availableLocales = $availableLocales;
        $this->defaultLocale    = $defaultLocale;
    }

    /**
     * @param Locale $locale
     * @return bool
     */
    public function isAvailableLocale(Locale $locale)
    {
        return $this->availableLocales->contains($locale);
    }

    /**
     * @return Locale
     */
    public function getLocale()
    {
        $locale = $this->localeRepository->findLocale();

        if ($locale) {
            return $locale;
        }

        return $this->defaultLocale;
    }

    /**
     * @param ApiUserInterface $user
     */
    public function saveLocaleOf(ApiUserInterface $user)
    {
        $this->localeRepository->save($user->getLocale());
    }

    /**
     * @return LocaleSet
     */
    public function getAvailableLocales()
    {
        return $this->availableLocales;
    }
}
