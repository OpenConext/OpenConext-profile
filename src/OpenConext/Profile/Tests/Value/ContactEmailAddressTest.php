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

use OpenConext\Profile\Value\ContactEmailAddress;
use PHPUnit\Framework\TestCase;

final class ContactEmailAddressTest extends TestCase
{

    /**
     * @test
     * @group Value
     */
    public function it_accepts_emails(): void
    {
        $email = new ContactEmailAddress('juliette.dupree+spam@that.invalid');
        $this->assertTrue((string) $email === 'juliette.dupree+spam@that.invalid');
    }

    /**
     * @test
     * @group Value
     */
    public function email_address_may_contain_mailto(): void
    {
        $emailAddressWithMailto = 'mailto:mail@domain.invalid';

        $emailAddress = new ContactEmailAddress($emailAddressWithMailto);

        $this->assertEquals($emailAddressWithMailto, (string) $emailAddress);
    }

    /**
     * @test
     * @group Value
     */
    public function two_emails_can_equal_each_other(): void
    {
        $url0 = new ContactEmailAddress('renee.dupree@datrijmtook.invalid');
        $url1 = new ContactEmailAddress('renee.dupree@datrijmtook.invalid');

        $this->assertTrue($url0->equals($url1));
    }

    /**
     * @test
     * @group Value
     */
    public function two_emails_can_differ(): void
    {
        $url0 = new ContactEmailAddress('renee.boulanger@vara.invalid');
        $url1 = new ContactEmailAddress('francois.boulanger@vara.invalid');

        $this->assertFalse($url0->equals($url1));
    }
}
