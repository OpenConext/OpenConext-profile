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

use OpenConext\Profile\Assert;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSet;
use Surfnet\SamlBundle\SAML2\Response\AssertionAdapter;

final class AuthenticatedUser
{
    /**
     * @var string
     */
    private $nameId;

    /**
     * @var AttributeSet
     */
    private $attributes;

    /**
     * @var array
     */
    private $authenticatingAuthorities;

    /**
     * @param AssertionAdapter $assertionAdapter
     * @param array $authenticatingAuthorities
     * @return AuthenticatedUser
     */
    public static function createFrom(AssertionAdapter $assertionAdapter, array $authenticatingAuthorities)
    {
        return new self(
            $assertionAdapter->getNameId(),
            $assertionAdapter->getAttributeSet(),
            $authenticatingAuthorities
        );
    }

    /**
     * @param string $nameId
     * @param AttributeSet $attributes
     * @param array $authenticatingAuthorities
     */
    private function __construct($nameId, AttributeSet $attributes, array $authenticatingAuthorities)
    {
        Assert::string($nameId);

        $this->nameId                    = $nameId;
        $this->attributes                = $attributes;
        $this->authenticatingAuthorities = $authenticatingAuthorities;
    }

    /**
     * @return string
     */
    public function getNameId()
    {
        return $this->nameId;
    }

    /**
     * @return AttributeSet
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return array
     */
    public function getAuthenticatingAuthorities()
    {
        return $this->authenticatingAuthorities;
    }

    /**
     * Using toString in order to comply with AbstractToken's setUser method,
     * which uses the string representation to detect changes in the user object.
     * Not implementing a UserInterface, because methods defined there will not be used.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nameId;
    }
}
