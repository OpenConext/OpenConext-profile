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

use OpenConext\Profile\Api\ApiUserInterface;
use OpenConext\Profile\Value\ContactEmailAddress;
use OpenConext\Profile\Value\Locale;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSet;
use Symfony\Component\Security\Core\User\UserInterface;

final class User implements ApiUserInterface
{
    private ?ContactEmailAddress $supportContactEmail = null;

    public function __construct(
        private readonly AuthenticatedUser $authenticatedUser,
        private Locale                     $locale,
    ) {
    }

    /**
     * @return UserInterface
     */
    public function withSupportContactEmail(ContactEmailAddress $supportContactEmail): UserInterface|User
    {
        $newUser = clone $this;
        $newUser->supportContactEmail = $supportContactEmail;

        return $newUser;
    }

    public function switchLocaleTo(Locale $locale): void
    {
        $this->locale = $locale;
    }

    public function hasSupportContactEmail(): bool
    {
        return $this->supportContactEmail !== null;
    }

    public function getSupportContactEmail(): ?ContactEmailAddress
    {
        return $this->supportContactEmail;
    }

    public function getLocale(): Locale
    {
        return $this->locale;
    }

    public function getAttributes(): AttributeSet
    {
        return $this->authenticatedUser->getAttributesFiltered();
    }

    public function getId(): string
    {
        return $this->authenticatedUser->getNameId();
    }
}
