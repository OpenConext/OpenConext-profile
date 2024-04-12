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

use OpenConext\Profile\Exception\InvalidArgumentException;
use OpenConext\Profile\Tests\DataProvider;
use OpenConext\Profile\Value\EmailAddress;
use PHPUnit\Framework\TestCase;

final class EmailAddressTest extends TestCase
{
    use DataProvider;

    /**
     * @test
     * @group Value
     */
    public function it_accepts_emails(): void
    {
        $email = new EmailAddress('juliette.dupree+spam@that.invalid');

        $this->assertTrue((string) $email === 'juliette.dupree+spam@that.invalid');
    }


    /**
     * @test
     * @group Value
     *
     *
     */
    public function email_address_may_not_contain_mailto(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $emailAddressWithMailto = 'mailto:mail@domain.invalid';
        new EmailAddress($emailAddressWithMailto);
    }

    /**
     * @test
     * @group Value
     * @dataProvider invalidEmailAddressProvider
     *
     *
     */
    public function email_address_must_be_valid($invalidEmailAddress): void
    {
        $this->expectException(InvalidArgumentException::class);
        new EmailAddress($invalidEmailAddress);
    }

    /**
     * @test
     * @group Value
     */
    public function two_emails_can_equal_each_other(): void
    {
        $url0 = new EmailAddress('renee.dupree@datrijmtook.invalid');
        $url1 = new EmailAddress('renee.dupree@datrijmtook.invalid');

        $this->assertTrue($url0->equals($url1));
    }

    /**
     * @test
     * @group Value
     */
    public function two_emails_can_differ(): void
    {
        $url0 = new EmailAddress('renee.boulanger@vara.invalid');
        $url1 = new EmailAddress('francois.boulanger@vara.invalid');

        $this->assertFalse($url0->equals($url1));
    }

    public function invalidEmailAddressProvider(): array
    {
        return [
            ['empty'       => ''],
            ['without @'   => 'no-at-mark.invalid'],
            ['two @'       => 'two@marks@email.invalid'],
            ['without tld' => 'invalid@email']
        ];
    }
}
