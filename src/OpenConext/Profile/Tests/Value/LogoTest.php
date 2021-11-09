<?php

/**
 * Copyright 2021 SURFnet B.V.
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

use OpenConext\Profile\Value\Logo;
use PHPUnit\Framework\TestCase;

class LogoTest extends TestCase
{
    /**
     * @test
     * @group Logo
     * @group Value
     */
    public function hasUrlReturnsFalseWhenThereIsNoUrl()
    {
        $logoWithoutUrl = new Logo(null, null, null);
        $otherLogoWithoutUrl = new Logo('', '', '');
        $this->assertFalse($logoWithoutUrl->hasUrl());
        $this->assertFalse($otherLogoWithoutUrl->hasUrl());
    }

    /**
     * @test
     * @group Logo
     * @group Value
     */
    public function hasUrlReturnsTrueWhenUrlIsPresent()
    {
        $logoWithUrl = new Logo("https//fake.logo/logo.jpg", null, null);
        $this->assertTrue($logoWithUrl->hasUrl());
    }
}
