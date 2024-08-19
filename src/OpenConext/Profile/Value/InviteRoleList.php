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

namespace OpenConext\Profile\Value;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class InviteRoleList implements IteratorAggregate, Countable
{
    /**
     * @var InviteRole[]
     */
    private array $roles = [];

    /**
     * @param InviteRole[] $inviteRoles
     */
    public function __construct(array $inviteRoles) {
        foreach ($inviteRoles as $invite) {
            $this->initializeWith($invite);
        }
    }

    private function initializeWith(InviteRole $inviteRole,
    ): void {
        $this->roles[] = $inviteRole;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->roles);
    }

    public function count(): int
    {
        return count($this->roles);
    }
}
