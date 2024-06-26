<?php

declare(strict_types = 1);

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
use OpenConext\Profile\Value\EntityId;
use Stringable;
use Surfnet\SamlBundle\SAML2\Attribute\Attribute;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSet;
use Surfnet\SamlBundle\SAML2\Response\AssertionAdapter;
use Symfony\Component\Security\Core\User\UserInterface;

final class AuthenticatedUser implements Stringable, UserInterface
{
    /**
     * A list of blacklisted attributes defined by their Urn OID
     * @var string[]
     */
    private static array $blacklistedAttributes = [
        'urn:oid:1.3.6.1.4.1.1076.20.40.40.1',
        'urn:oid:1.3.6.1.4.1.1466.115.121.1.15',
    ];

    /**
     * @param EntityId[] $authenticatingAuthorities
     */
    public static function createFrom(
        AssertionAdapter $assertionAdapter,
        array $authenticatingAuthorities,
    ): AuthenticatedUser {

        $attributes = [];

        /** @var Attribute $attribute */
        foreach ($assertionAdapter->getAttributeSet() as $attribute) {
            $definition = $attribute->getAttributeDefinition();

            // We only want to replace the eduPersonTargetedID attribute value as that is a nested NameID attribute
            if ($definition->getName() !== 'eduPersonTargetedID') {
                $attributes[] = $attribute;
                continue;
            }

            $eptiValues = $attribute->getValue();
            $attributes[] = new Attribute($definition, [$eptiValues[0]->getValue()]);
        }

        return new self($assertionAdapter->getNameId(), AttributeSet::create($attributes), $authenticatingAuthorities);
    }

    /**
     * @param EntityId[] $authenticatingAuthorities
     */
    private function __construct(
        private readonly string       $nameId,
        private readonly AttributeSet $attributes,
        /**  @var EntityId[] */
        private readonly array        $authenticatingAuthorities,
    ) {
        Assert::allIsInstanceOf($authenticatingAuthorities, EntityId::class);
    }

    public function getNameId(): string
    {
        return $this->nameId;
    }

    /**
     * @return EntityId[]
     */
    public function getAuthenticatingAuthorities(): array
    {
        return $this->authenticatingAuthorities;
    }

    /**
     * Using toString in order to comply with AbstractToken's setUser method,
     * which uses the string representation to detect changes in the user object.
     * Not implementing a UserInterface, because methods defined there will not be used.
     */
    public function __toString(): string
    {
        return $this->nameId;
    }

    public function getAttributesFiltered(): AttributeSet
    {
        $filtered = [];

        foreach ($this->attributes as $attribute) {
            assert($attribute instanceof Attribute);

            // Filter out blacklisted attributes
            if (in_array($attribute->getAttributeDefinition()->getUrnOid(), self::$blacklistedAttributes)) {
                continue;
            }

            $filtered[] = $attribute;
        }
        return AttributeSet::create($filtered);
    }

    public function getAttributes(): AttributeSet
    {
        return $this->attributes;
    }

    public function getRoles(): array
    {
        return ["ROLE_USER"];
    }

    public function eraseCredentials(): array
    {
        return [];
    }

    public function getUserIdentifier(): string
    {
        return $this->getNameId();
    }
}
