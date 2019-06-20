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

namespace OpenConext\Profile\Api;

use OpenConext\Profile\Value\ContactEmailAddress;
use OpenConext\Profile\Value\Locale;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSet;

interface ApiUserInterface
{
    /**
     * @param Locale $locale
     * @return void
     */
    public function switchLocaleTo(Locale $locale);

    /**
     * @return bool
     */
    public function hasSupportContactEmail();

    /**
     * @return null|ContactEmailAddress
     */
    public function getSupportContactEmail();

    /**
     * @return Locale
     */
    public function getLocale();

    /**
     * @return AttributeSet
     */
    public function getAttributes();

    /**
     * @return string
     */
    public function getId();
}
