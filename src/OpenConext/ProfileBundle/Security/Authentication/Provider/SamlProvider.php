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
use OpenConext\ProfileBundle\Security\Authentication\Token\SamlToken;
use SAML2\Assertion;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDictionary;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSet;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSetFactory;
use Surfnet\SamlBundle\SAML2\Attribute\ConfigurableAttributeSetFactory;
use Surfnet\SamlBundle\Security\Authentication\Provider\SamlProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SamlProvider implements SamlProviderInterface, UserProviderInterface
{
    /**
     * @var AttributeDictionary
     */
    private $attributeDictionary;

    public function __construct(AttributeDictionary $attributeDictionary)
    {
        $this->attributeDictionary = $attributeDictionary;
    }

    public function authenticate(TokenInterface $token)
    {
        ConfigurableAttributeSetFactory::configureWhichAttributeSetToCreate(AttributeSetWithFallbacks::class);
        $translatedAssertion = $this->attributeDictionary->translate($token->assertion);

        $authenticatingAuthorities = array_map(
            function ($authenticatingAuthority) {
                return new EntityId($authenticatingAuthority);
            },
            $token->assertion->getAuthenticatingAuthority(),
        );

        $user = AuthenticatedUser::createFrom($translatedAssertion, $authenticatingAuthorities);

        $authenticatedToken = new SamlToken(['ROLE_USER']);
        $authenticatedToken->setUser($user);

        return $authenticatedToken;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof SamlToken;
    }

    public function getNameId(Assertion $assertion): string
    {
        // TODO: Implement getNameId() method.
    }

    public function getUser(Assertion $assertion): UserInterface
    {
        // TODO: Implement getUser() method.
    }

    public function refreshUser(UserInterface $user): void
    {
        // TODO: Implement refreshUser() method.
    }

    public function supportsClass(string $class)
    {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        throw new BadMethodCallException('Use `getUser` to load a user by username');
    }
}
