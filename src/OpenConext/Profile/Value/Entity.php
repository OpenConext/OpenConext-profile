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

namespace OpenConext\Profile\Value;

use OpenConext\Profile\Assert;
use OpenConext\Profile\Exception\LogicException;
use OpenConext\Profile\Value\EntityId;
use OpenConext\Profile\Value\EntityType;
use Stringable;

final readonly class Entity implements Stringable
{
    /**
     * @return Entity
     */
    public static function fromDescriptor(
        array $descriptor,
    ): self {
        Assert::count($descriptor, 2);

        return match ($descriptor[1]) {
            'sp' => new Entity(new EntityId($descriptor[0]), EntityType::SP()),
            'idp' => new Entity(new EntityId($descriptor[0]), EntityType::IdP()),
            default => throw new LogicException('Entity descriptor type neither "sp" nor "idp"'),
        };
    }

    public function __construct(
        private EntityId $entityId,
        private EntityType $entityType,
    ) {
    }

    public function getEntityId(): EntityId
    {
        return $this->entityId;
    }

    public function getEntityType(): EntityType
    {
        return $this->entityType;
    }

    public function equals(
        Entity $other,
    ): bool {
        return $this->entityId->equals($other->entityId) && $this->entityType->equals($other->entityType);
    }

    public function __toString(): string
    {
        return $this->entityId . '(' . $this->entityType . ')';
    }
}
