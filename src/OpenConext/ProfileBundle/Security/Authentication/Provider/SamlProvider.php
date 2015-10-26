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

use OpenConext\ProfileBundle\Security\Authentication\Entity\User;
use OpenConext\ProfileBundle\Security\Authentication\Token\SamlToken;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDictionary;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SamlProvider implements AuthenticationProviderInterface
{
    /**
     * @var \Surfnet\SamlBundle\SAML2\Attribute\AttributeDictionary
     */
    private $attributeDictionary;

    public function __construct(AttributeDictionary $attributeDictionary)
    {
        $this->attributeDictionary = $attributeDictionary;
    }

    public function authenticate(TokenInterface $token)
    {
        $translatedAssertion = $this->attributeDictionary->translate($token->assertion);

        $user = new User();

        $user->nameID =  $translatedAssertion->getNameID();
        $user->institution = $translatedAssertion->getAttribute('schacHomeOrganization');
        $user->email = $translatedAssertion->getAttribute('mail');
        $user->commonName = $translatedAssertion->getAttribute('commonName');

        $authenticatedToken = new SamlToken(['ROLE_USER']);

        $authenticatedToken->setUser($user);

        return $authenticatedToken;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof SamlToken;
    }
}
