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

namespace OpenConext\EngineBlockApiClientBundle\Tests\Value;

use OpenConext\EngineBlockApiClientBundle\Value\ContactPersonListFactory;
use OpenConext\Profile\Value\Consent;
use OpenConext\Profile\Value\ContactPerson;
use OpenConext\Profile\Value\ContactPersonList;
use OpenConext\Profile\Value\ContactType;
use OpenConext\Profile\Value\ContactEmailAddress;
use PHPUnit_Framework_TestCase as TestCase;

final class ContactPersonListFactoryTest extends TestCase
{
    /**
     * @test
     * @group ContactPerson
     */
    public function contact_person_list_can_be_deserialized()
    {
        $given = [
            'contact_persons' => [
                [
                    'contact_type'  => 'technical',
                    'email_address' => 'invalid@email.address'
                ],                [
                    'contact_type'  => 'support',
                    'email_address' => 'invalid@email.address'
                ],
                [
                    'contact_type' => 'administrative',
                    'email_address' => 'invalid@email.address'
                ],
                [
                    'contact_type'  => 'billing',
                    'email_address' => 'invalid@email.address'
                ],
                [
                    'contact_type' => 'other',
                    'email_address' => 'invalid@email.address'
                ],

            ]
        ];

        $expectedContactPersonList = new ContactPersonList([
            new ContactPerson(
                new ContactType(ContactType::TYPE_TECHNICAL),
                new ContactEmailAddress('invalid@email.address')
            ),
            new ContactPerson(
                new ContactType(ContactType::TYPE_SUPPORT),
                new ContactEmailAddress('invalid@email.address')
            ),
            new ContactPerson(
                new ContactType(ContactType::TYPE_ADMINISTRATIVE),
                new ContactEmailAddress('invalid@email.address')
            ),
            new ContactPerson(
                new ContactType(ContactType::TYPE_BILLING),
                new ContactEmailAddress('invalid@email.address')
            ),
            new ContactPerson(
                new ContactType(ContactType::TYPE_OTHER),
                new ContactEmailAddress('invalid@email.address')
            ),
        ]);

        $this->assertEquals($expectedContactPersonList, ContactPersonListFactory::createListFromMetadata($given));
    }

    /**
     * @test
     * @group ContactPerson
     */
    public function contact_person_list_with_duplicates_can_be_deserialized()
    {
        $given = [
            'contact_persons' => [
                [
                    'contact_type'  => 'support',
                    'email_address' => 'invalid@email.address'
                ],                [
                    'contact_type'  => 'support',
                    'email_address' => 'invalid@email.address'
                ],
            ]
        ];

        $expectedContactPersonList = new ContactPersonList([
            new ContactPerson(
                new ContactType(ContactType::TYPE_SUPPORT),
                new ContactEmailAddress('invalid@email.address')
            ),
            new ContactPerson(
                new ContactType(ContactType::TYPE_SUPPORT),
                new ContactEmailAddress('invalid@email.address')
            ),
        ]);

        $this->assertEquals($expectedContactPersonList, ContactPersonListFactory::createListFromMetadata($given));
    }

    /**
     * @test
     * @group ContactPerson
     */
    public function contact_person_list_with_contact_person_without_email_address_can_be_deserialized()
    {
        $given = [
            'contact_persons' => [
                [
                    'contact_type'  => 'technical',
                    'email_address' => 'invalid@email.address'
                ],                [
                    'contact_type'  => 'support',
                ],
            ]
        ];

        $expectedContactPersonList = new ContactPersonList([
            new ContactPerson(
                new ContactType(ContactType::TYPE_TECHNICAL),
                new ContactEmailAddress('invalid@email.address')
            ),
            new ContactPerson(
                new ContactType(ContactType::TYPE_SUPPORT),
                null
            ),
        ]);

        $this->assertEquals($expectedContactPersonList, ContactPersonListFactory::createListFromMetadata($given));
    }
}
