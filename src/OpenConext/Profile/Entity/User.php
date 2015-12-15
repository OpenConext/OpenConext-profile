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

namespace OpenConext\Profile\Entity;

use OpenConext\Profile\Api\User as UserInterface;
use OpenConext\Profile\Value\Locale;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSet;

final class User implements UserInterface
{
    /**
     * @var AuthenticatedUser
     */
    private $authenticatedUser;

    /**
     * @var Locale
     */
    private $locale;

    /**
     * @param AuthenticatedUser $authenticatedUser
     * @param Locale $locale
     */
    public function __construct(AuthenticatedUser $authenticatedUser, Locale $locale)
    {
        $this->authenticatedUser = $authenticatedUser;
        $this->locale            = $locale;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function switchLocaleTo(Locale $locale)
    {
        $this->locale = $locale;
    }

    public function getAttributes()
    {
        return $this->authenticatedUser->getAttributes();
    }

    public function getId()
    {
        return $this->authenticatedUser->getNameId();
    }
}
