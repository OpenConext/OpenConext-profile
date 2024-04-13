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

final class Locale implements Stringable
{
    private readonly string $locale;

    /**
     * @param string $locale
     */
    public function __construct(
        $locale,
    ) {
        Assert::string($locale);
        Assert::notEmpty($locale);

        $this->locale = $locale;
    }

    /**
     * @return bool
     */
    public function equals(
        Locale $other,
    ): bool {
        return $this->locale === $other->locale;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->locale;
    }
}
