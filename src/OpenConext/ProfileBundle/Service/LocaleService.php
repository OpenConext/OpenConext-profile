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

use OpenConext\Profile\Assert;
use OpenConext\Profile\Repository\LocaleRepository;
use OpenConext\Profile\Value\Locale;
use OpenConext\Profile\Api\User;

final class LocaleService
{
    /**
     * @var LocaleRepository
     */
    private $localeRepository;

    /**
     * @var string[]
     */
    private $availableLocales;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @param LocaleRepository $localeRepository
     * @param string[] $availableLocales
     * @param Locale $defaultLocale
     */
    public function __construct(LocaleRepository $localeRepository, array $availableLocales, Locale $defaultLocale)
    {
        Assert::allString($availableLocales);

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
        return in_array($this->availableLocales, $locale->getLocale());
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
     * @param User $user
     */
    public function saveLocaleOf(User $user)
    {
        $this->localeRepository->save($user->getLocale());
    }

    /**
     * @return string[]
     */
    public function getAvailableLocales()
    {
        return $this->availableLocales;
    }
}
