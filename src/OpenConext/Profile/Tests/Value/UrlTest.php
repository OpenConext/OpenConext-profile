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

use OpenConext\Profile\Tests\DataProvider;
use OpenConext\Profile\Value\Url;
use PHPUnit_Framework_TestCase as TestCase;

class UrlTest extends TestCase
{
    use DataProvider;

    /**
     * @test
     * @group value
     */
    public function it_can_be_deserialised()
    {
        (new Url('https://domain.invalid'));
    }

    /**
     * @test
     * @group value
     * @dataProvider nonStringProvider
     * @expectedException \OpenConext\Profile\Exception\AssertionFailedException
     *
     * @param mixed $nonString
     */
    public function it_doesnt_accept_anything_else_than_strings($nonString)
    {
        (new Url($nonString));
    }

    /**
     * @test
     * @group value
     */
    public function its_scheme_can_be_verified()
    {
        $this->assertTrue((new Url('https://sp.invalid'))->hasScheme('https'));
    }
}
