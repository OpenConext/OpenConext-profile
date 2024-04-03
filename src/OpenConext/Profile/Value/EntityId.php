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

namespace OpenConext\Profile\Value;

use OpenConext\Profile\Assert;

final class EntityId
{


    public function __construct(private readonly string $entityId)
    {
        Assert::notEmpty($entityId);
        Assert::notBlank($entityId);
    }

    /**
     * @param EntityId $other
     * @return bool
     */
    public function equals(EntityId $other)
    {
        return $this->entityId === $other->entityId;
    }

    /**
     * @return string
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    public function __toString()
    {
        return $this->entityId;
    }
}
