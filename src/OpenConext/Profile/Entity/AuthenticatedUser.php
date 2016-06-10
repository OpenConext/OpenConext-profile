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
use OpenConext\Profile\Exception\RuntimeException;
use OpenConext\Profile\Value\EntityId;
use Surfnet\SamlBundle\SAML2\Attribute\Attribute;
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
     * @var EntityId[]
     */
    private $authenticatingAuthorities;

    /**
     * @param AssertionAdapter $assertionAdapter
     * @param EntityId[] $authenticatingAuthorities
     *
     * @return AuthenticatedUser
     * @throws RuntimeException
     */
    public static function createFrom(AssertionAdapter $assertionAdapter, array $authenticatingAuthorities)
    {
        $attributes = [];

        /** @var Attribute $attribute */
        foreach ($assertionAdapter->getAttributeSet() as $attribute) {
            $definition = $attribute->getAttributeDefinition();

            // We only want to replace the eduPersonTargetedID attribute value as that is a nested NameID attribute
            if ($definition->getName() !== 'eduPersonTargetedID') {
                $attributes[] = $attribute;
                continue;
            }

            /** @var \DOMNodeList[] $eptiValues */
            $eptiValues = $attribute->getValue();
            $eptiDomNodeList = $eptiValues[0];

            if (!$eptiDomNodeList instanceof \DOMNodeList || $eptiDomNodeList->length !== 1) {
                throw new RuntimeException(
                    sprintf(
                        'EPTI attribute must contain exactly one NameID element as value, received: %s',
                        print_r($eptiValues, true)
                    )
                );
            }

            $eptiValue  = $eptiDomNodeList->item(0);
            $eptiNameId = \SAML2_Utils::parseNameId($eptiValue);

            $attributes[] = new Attribute($definition, [$eptiNameId['Value']]);
        }

        return new self(
            $assertionAdapter->getNameId(),
            AttributeSet::create($attributes),
            $authenticatingAuthorities
        );
    }

    /**
     * @param string $nameId
     * @param AttributeSet $attributes
     * @param EntityId[] $authenticatingAuthorities
     */
    private function __construct($nameId, AttributeSet $attributes, array $authenticatingAuthorities)
    {
        Assert::string($nameId);
        Assert::allIsInstanceOf($authenticatingAuthorities, '\OpenConext\Profile\Value\EntityId');

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
     * @return EntityId[]
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
