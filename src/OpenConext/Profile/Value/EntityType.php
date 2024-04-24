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

namespace OpenConext\Profile\Value;

use Assert\AssertionFailedException;
use OpenConext\Profile\Assert;
use Stringable;

final class EntityType implements Stringable
{
    public const TYPE_SP  = 'saml20-sp';
    public const TYPE_IDP = 'saml20-idp';

    /**
     * @param string $type
     * @throws AssertionFailedException
     */
    public function __construct(
        private readonly string $type,
    ) {
        Assert::inArray($type, [self::TYPE_SP, self::TYPE_IDP]);
    }

    /**
     * Creates a new ServiceProvider Type
     * @return EntityType
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function SP(): EntityType
    {
        return new EntityType(self::TYPE_SP);
    }

    // @codingStandardsIgnoreStart
    /**
     * Creates a new IdentityProvider Type
     * @return EntityType
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public static function IdP(): \OpenConext\Profile\Value\EntityType
    {
        return new EntityType(self::TYPE_IDP);
    }

    public function isSP(): bool
    {
        return $this->type === self::TYPE_SP;
    }

    public function isIdP(): bool
    {
        return $this->type === self::TYPE_IDP;
    }
    // @codingStandardsIgnoreEnd
    public function equals(
        EntityType $other,
    ): bool {
        return $this->type === $other->type;
    }

    public function __toString(): string
    {
        return $this->type;
    }
}
