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

namespace OpenConext\ProfileBundle\Profile\Repository;

use OpenConext\Profile\Repository\LocaleRepository as LocaleRepositoryInterface;
use OpenConext\Profile\Value\Locale;
use OpenConext\ProfileBundle\Service\LocaleStorageDriver;

class LocaleRepository implements LocaleRepositoryInterface
{
    /**
     * @var LocaleStorageDriver
     */
    private $localeStorageDriver;

    public function __construct(LocaleStorageDriver $localeStorageDriver)
    {
        $this->localeStorageDriver = $localeStorageDriver;
    }

    public function findLocale()
    {
        return $this->localeStorageDriver->find();
    }

    public function save(Locale $locale)
    {
        $this->localeStorageDriver->save($locale);
    }
}
