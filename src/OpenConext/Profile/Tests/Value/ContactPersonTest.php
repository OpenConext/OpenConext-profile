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

use OpenConext\Profile\Value\ContactPerson;
use OpenConext\Profile\Value\ContactType;
use OpenConext\Profile\Value\ContactEmailAddress;
use PHPUnit\Framework\TestCase;

class ContactPersonTest extends TestCase
{
    /**
     * @test
     * @group ContactPerson
     */
    public function given_contact_type_can_be_asserted()
    {
        $contactType     = new ContactType(ContactType::TYPE_TECHNICAL);
        $sameContactType = new ContactType(ContactType::TYPE_TECHNICAL);

        $contactPerson = new ContactPerson($contactType);

        $hasGivenContactType = $contactPerson->hasContactTypeOf($sameContactType);

        $this->assertTrue($hasGivenContactType);
    }

    /**
     * @test
     * @group ContactPerson
     */
    public function given_contact_type_cannot_be_asserted()
    {
        $contactType          = new ContactType(ContactType::TYPE_TECHNICAL);
        $differentContactType = new ContactType(ContactType::TYPE_SUPPORT);

        $contactPerson = new ContactPerson($contactType);

        $hasGivenContactType = $contactPerson->hasContactTypeOf($differentContactType);

        $this->assertFalse($hasGivenContactType);
    }

    /**
     * @test
     * @group ContactPerson
     */
    public function presence_of_email_address_can_be_asserted()
    {
        $contactPerson = new ContactPerson(
            new ContactType(
                ContactType::TYPE_TECHNICAL,
            ),
            new ContactEmailAddress('invalid@email.address'),
        );

        $hasEmailAddress = $contactPerson->hasEmailAddress();

        $this->assertTrue($hasEmailAddress);
    }

    /**
     * @test
     * @group ContactPerson
     */
    public function presence_of_email_address_cannot_be_asserted()
    {
        $contactPerson = new ContactPerson(new ContactType(ContactType::TYPE_TECHNICAL));

        $hasEmailAddress = $contactPerson->hasEmailAddress();

        $this->assertFalse($hasEmailAddress);
    }

    /**
     * @test
     * @group ContactPerson
     */
    public function given_email_address_can_be_retrieved()
    {
        $givenEmailAddress = new ContactEmailAddress('invalid@email.address');

        $contactPerson = new ContactPerson(new ContactType(ContactType::TYPE_TECHNICAL), $givenEmailAddress);
        $actualEmailAddress = $contactPerson->getEmailAddress();

        $this->assertSame($givenEmailAddress, $actualEmailAddress);
    }
}
