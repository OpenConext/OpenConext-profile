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

use OpenConext\Profile\Assert;
use Stringable;

final class ContactType implements Stringable
{
    public const TYPE_TECHNICAL = 'technical';
    public const TYPE_SUPPORT = 'support';
    public const TYPE_ADMINISTRATIVE = 'administrative';
    public const TYPE_BILLING = 'billing';
    public const TYPE_OTHER = 'other';

    private readonly string $contactType;

    /**
     * @param string $contactType
     */
    public function __construct(
        $contactType,
    ) {
        Assert::string($contactType);
        Assert::choice(
            $contactType,
            [self::TYPE_TECHNICAL, self::TYPE_SUPPORT, self::TYPE_ADMINISTRATIVE, self::TYPE_BILLING, self::TYPE_OTHER],
            '"%s" is not one of the valid ContactTypes: %s',
        );

        $this->contactType = $contactType;
    }

    public function equals(
        ContactType $other,
    ): bool {
        return $this->contactType === $other->contactType;
    }

    public function __toString(): string
    {
        return $this->contactType;
    }
}
