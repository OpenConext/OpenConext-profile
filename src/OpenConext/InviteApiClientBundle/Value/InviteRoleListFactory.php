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
use TypeError;

final class InviteRoleListFactory
{
    /**
     * @param array<int, array<string, mixed>> $data
     */
    public static function createList(
        array $data,
    ): InviteRoleList {
        $roles = array_map(
            self::createInviteRole(...),
            $data,
        );

        return new InviteRoleList($roles);
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function createInviteRole(
        array $data,
    ): InviteRole {
        $applications = [];
        if (array_key_exists('applications', $data)) {
            $applications = self::requireApplications($data['applications']);
        }

        $name = self::requireString($data['name'] ?? null, 'name');
        $description = self::requireString($data['description'] ?? $name, 'description');

        return new InviteRole($name, $description, $applications);
    }

    private static function requireString(
        mixed $value,
        string $field,
    ): string {
        if (!is_string($value)) {
            throw new TypeError(sprintf('Invite role field "%s" must be a string', $field));
        }

        return $value;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function requireApplications(
        mixed $value,
    ): array {
        if (!is_array($value)) {
            throw new TypeError('Invite role applications must be an array');
        }

        /** @var array<int, array<string, mixed>> $value */
        return $value;
    }
}
