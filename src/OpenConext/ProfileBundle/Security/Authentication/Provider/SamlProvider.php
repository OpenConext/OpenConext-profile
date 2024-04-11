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

namespace OpenConext\ProfileBundle\Security\Authentication\Provider;

use BadMethodCallException;
use OpenConext\Profile\Entity\AuthenticatedUser;
use OpenConext\Profile\Value\EntityId;
use OpenConext\ProfileBundle\Attribute\AttributeSetWithFallbacks;
use SAML2\Assertion;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDictionary;
use Surfnet\SamlBundle\SAML2\Attribute\ConfigurableAttributeSetFactory;
use Surfnet\SamlBundle\Security\Authentication\Provider\SamlProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SamlProvider implements SamlProviderInterface, UserProviderInterface
{
    public function __construct(
        private readonly AttributeDictionary $attributeDictionary,
    ) {
    }

    public function getNameId(Assertion $assertion): string
    {
        return $this->attributeDictionary->translate($assertion)->getNameID();
    }

    public function getUser(Assertion $assertion): UserInterface
    {
        ConfigurableAttributeSetFactory::configureWhichAttributeSetToCreate(AttributeSetWithFallbacks::class);
        $translatedAssertion = $this->attributeDictionary->translate($assertion);

        $authenticatingAuthorities = array_map(
            fn($authenticatingAuthority) => new EntityId($authenticatingAuthority),
            $assertion->getAuthenticatingAuthority(),
        );

        return AuthenticatedUser::createFrom($translatedAssertion, $authenticatingAuthorities);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === AuthenticatedUser::class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        throw new BadMethodCallException('Use `getUser` to load a user by username');
    }
}
