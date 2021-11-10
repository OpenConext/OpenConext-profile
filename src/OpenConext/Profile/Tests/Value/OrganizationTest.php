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
use OpenConext\Profile\Value\Organization;
use PHPUnit\Framework\TestCase;

class OrganizationTest extends TestCase
{
    /**
     * @test
     * @group Organization
     * @group Value
     */
    public function getDisplayNameReturnsCorrectValue()
    {
        $displayName = ['en' => 'English displayname', 'pt' => null, 'nl' => 'Nederlandse displaynaam'];
        $name = ['en' => 'English name', 'pt' => null, 'nl' => 'Nederlandse naam'];
        $logo = new Logo(null, null, null);
        $organization = new Organization($displayName, $name, $logo);

        $this->assertEquals($organization->getDisplayName('en'), 'English displayname');
        $this->assertEquals($organization->getDisplayName('pt'), 'English name');
        $this->assertEquals($organization->getDisplayName('nl'), 'Nederlandse displaynaam');

        $displayName['nl'] = '';
        $organization2 = new Organization($displayName, $name, $logo);
        $this->assertEquals($organization2->getDisplayName('nl'), 'Nederlandse naam');

        $name['nl'] = '';
        $organization3 = new Organization($displayName, $name, $logo);
        $this->assertEquals($organization3->getDisplayName('nl'), 'English name');

        $displayName['en'] = '';
        $name['en'] = '';
        $organization4 = new Organization($displayName, $name, $logo);
        $this->assertEquals($organization4->getDisplayName('nl'), '');
    }
}
