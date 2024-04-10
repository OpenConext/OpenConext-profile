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
use PHPUnit\Framework\TestCase;
use OpenConext\Profile\Exception\InvalidArgumentException;

class UrlTest extends TestCase
{
    use DataProvider;

    /**
     * @test
     * @group Value
     */
    public function one_can_be_created_with_a_valid_url(): void
    {
        $url = new Url('https://domain.invalid');

        $this->assertTrue((string) $url === 'https://domain.invalid');
    }

    /**
     * @test
     * @group Value
     */
    public function one_without_scheme_fails_validation(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Url('www.exampe.org');
    }
}
