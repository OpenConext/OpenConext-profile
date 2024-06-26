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

use OpenConext\Profile\Value\ContactType;
use PHPUnit\Framework\TestCase;

class ContactTypeTest extends TestCase
{
    /**
     * @test
     * @group ContactPerson
     */
    public function two_contact_types_can_equal_each_other(): void
    {
        $contactType     = new ContactType(ContactType::TYPE_TECHNICAL);
        $sameContactType = new ContactType(ContactType::TYPE_TECHNICAL);

        $contactTypesAreEqual = $contactType->equals($sameContactType);

        $this->assertTrue($contactTypesAreEqual);
    }

    /**
     * @test
     * @group ContactPerson
     */
    public function two_contact_types_can_differ(): void
    {
        $contactType          = new ContactType(ContactType::TYPE_TECHNICAL);
        $differentContactType = new ContactType(ContactType::TYPE_ADMINISTRATIVE);

        $contactTypesAreEqual = $contactType->equals($differentContactType);

        $this->assertFalse($contactTypesAreEqual);
    }
}
