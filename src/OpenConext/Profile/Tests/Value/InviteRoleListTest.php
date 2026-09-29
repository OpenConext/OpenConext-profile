<?php

declare(strict_types = 1);

/**
 * Copyright 2024 SURFnet B.V.
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

use OpenConext\Profile\Value\InviteRole;
use OpenConext\Profile\Value\InviteRoleList;
use PHPUnit\Framework\TestCase;

class InviteRoleListTest extends TestCase
{
    public function test_it_can_order_by_application_display_name(): void
    {
        $list = new InviteRoleList([
            $this->buildInviteRole('Role C', 'C-application'),
            $this->buildInviteRole('Role A', 'A-application'),
            $this->buildInviteRole('Role B', 'B-application'),
        ]);

        $list->sortByApplicationDisplayName('en');

        $sorted = array_values(iterator_to_array($list->getIterator()));
        $this->assertCount(3, $sorted);
        $this->assertEquals('A-application', $sorted[0]->getApplications()[0]->getName('en'));
        $this->assertEquals('B-application', $sorted[1]->getApplications()[0]->getName('en'));
        $this->assertEquals('C-application', $sorted[2]->getApplications()[0]->getName('en'));
    }

    public function test_it_can_order_by_application_display_name_case_insensitively(): void
    {
        $list = new InviteRoleList([
            $this->buildInviteRole('Role C', 'C-application'),
            $this->buildInviteRole('Role A', 'a-application'),
            $this->buildInviteRole('Role B', 'B-application'),
        ]);

        $list->sortByApplicationDisplayName('en');

        $sorted = array_values(iterator_to_array($list->getIterator()));
        $this->assertCount(3, $sorted);
        $this->assertEquals('a-application', $sorted[0]->getApplications()[0]->getName('en'));
        $this->assertEquals('B-application', $sorted[1]->getApplications()[0]->getName('en'));
        $this->assertEquals('C-application', $sorted[2]->getApplications()[0]->getName('en'));
    }

    public function test_roles_without_applications_are_sorted_by_role_name(): void
    {
        $list = new InviteRoleList([
            $this->buildInviteRole('Role C', 'C-application'),
            $this->buildInviteRoleWithoutApplication('Role A'),
            $this->buildInviteRole('Role B', 'B-application'),
        ]);

        $list->sortByApplicationDisplayName('en');

        $sorted = array_values(iterator_to_array($list->getIterator()));
        $this->assertCount(3, $sorted);
        $this->assertEquals('B-application', $sorted[0]->getApplications()[0]->getName('en'));
        $this->assertEquals('C-application', $sorted[1]->getApplications()[0]->getName('en'));
        $this->assertEquals('Role A', $sorted[2]->getName());
    }

    public function test_it_can_order_nothing(): void
    {
        $list = new InviteRoleList([]);
        $list->sortByApplicationDisplayName('en');
        $this->assertCount(0, $list);
    }

    private function buildInviteRole(string $roleName, string $applicationNameEn): InviteRole
    {
        return new InviteRole($roleName, $roleName, [
            [
                'landingPage' => '',
                'nameEn' => $applicationNameEn,
                'nameNl' => $applicationNameEn,
                'organisationEn' => '',
                'organisationNl' => '',
                'logo' => '',
            ],
        ]);
    }

    private function buildInviteRoleWithoutApplication(string $roleName): InviteRole
    {
        return new InviteRole($roleName, $roleName, []);
    }
}
