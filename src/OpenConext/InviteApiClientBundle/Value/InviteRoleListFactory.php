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

namespace OpenConext\InviteApiClientBundle\Value;

use OpenConext\Profile\Value\InviteRole;
use OpenConext\Profile\Value\InviteRoleList;

final class InviteRoleListFactory
{
    public static function createList(
        mixed $data,
    ): InviteRoleList {
        $roles = array_map(
            self::createInviteRole(...),
            $data,
        );

        return new InviteRoleList($roles);
    }

    private static function createInviteRole(mixed $data): InviteRole
    {
        $applications = [];
        if (array_key_exists('applications', $data)) {
            $applications = $data['applications'];
        }
        return new InviteRole($data['name'], $data['description'], $applications);
    }
}
