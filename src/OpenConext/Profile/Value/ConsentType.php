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

final class ConsentType implements Stringable
{
    public const TYPE_EXPLICIT = 'explicit';
    public const TYPE_IMPLICIT = 'implicit';

    /**
     * @var string
     */
    private $consentType;

    /**
     * @return ConsentType
     */
    public static function explicit(): self
    {
        return new self(self::TYPE_EXPLICIT);
    }

    /**
     * @return ConsentType
     */
    public static function implicit(): self
    {
        return new self(self::TYPE_IMPLICIT);
    }

    /**
     * @throws AssertionFailedException
     */
    private function __construct(
        string $consentType,
    ) {
        Assert::choice(
            $consentType,
            [ConsentType::TYPE_EXPLICIT, ConsentType::TYPE_IMPLICIT],
            '"%s" is not one of the valid ConsentTypes: %s',
        );

        $this->consentType = $consentType;
    }

    public function equals(
        ConsentType $other,
    ): bool {
        return $this->consentType === $other->consentType;
    }

    public function isExplicit(): bool
    {
        return $this->consentType === self::TYPE_EXPLICIT;
    }

    public function isImplicit(): bool
    {
        return $this->consentType === self::TYPE_IMPLICIT;
    }

    public function __toString(): string
    {
        return $this->consentType;
    }
}
