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

namespace OpenConext\Profile\Tests\Value;

use OpenConext\Profile\Value\ConsentType;
use PHPUnit\Framework\TestCase;

class ConsentTypeTest extends TestCase
{
    /**
     * @test
     * @group Consent
     * @group Value
     */
    public function different_consent_types_are_not_equal(): void
    {
        $explicit = ConsentType::explicit();
        $implicit = ConsentType::implicit();

        $this->assertFalse($explicit->equals($implicit));
        $this->assertFalse($implicit->equals($explicit));
    }

    /**
     * @test
     * @group Consent
     * @group Value
     */
    public function same_type_of_consent_types_are_equal(): void
    {
        $explicit = ConsentType::explicit();
        $implicit = ConsentType::implicit();

        $this->assertTrue($explicit->equals(ConsentType::explicit()));
        $this->assertTrue($implicit->equals(ConsentType::implicit()));
    }
}
