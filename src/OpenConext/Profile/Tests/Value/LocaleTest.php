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

namespace OpenConext\Profile\Tests\Value;

use OpenConext\Profile\Value\Locale;
use PHPUnit\Framework\TestCase;

class LocaleTest extends TestCase
{
    /**
     * @test
     * @group Locale
     * @group Value
     */
    public function two_locales_with_different_values_are_not_equal(): void
    {
        $localeA = new Locale('a');
        $differentThanLocaleA = new Locale('b');

        $localesAreEqual = $localeA->equals($differentThanLocaleA);

        $this->assertFalse($localesAreEqual);
    }

    /**
     * @test
     * @group Locale
     * @group Value
     *
     */
    public function two_locales_with_the_same_value_are_equal(): void
    {
        $localeA = new Locale('a');
        $sameAsLocaleA = new Locale('a');

        $localesAreEqual = $localeA->equals($sameAsLocaleA);

        $this->assertTrue($localesAreEqual);
    }
}
