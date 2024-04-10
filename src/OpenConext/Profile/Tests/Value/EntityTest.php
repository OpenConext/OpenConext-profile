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

use PHPUnit\Framework\TestCase;
use OpenConext\Profile\Value\Entity;
use OpenConext\Profile\Value\EntityId;
use OpenConext\Profile\Value\EntityType;

class EntityTest extends TestCase
{
    /**
     * @test
     * @group Entity
     * @group Value
     */
    public function two_entities_with_the_same_entity_id_and_entity_type_are_equal(): void
    {
        $entityIdOne = new EntityId('one');
        $entityIdTwo = new EntityId('two');

        $sp  = EntityType::SP();
        $idp = EntityType::IdP();

        $base               = new Entity($entityIdOne, $sp);
        $theSame            = new Entity($entityIdOne, $sp);
        $differentType      = new Entity($entityIdOne, $idp);
        $differentId        = new Entity($entityIdTwo, $sp);
        $differentIdAndType = new Entity($entityIdTwo, $idp);

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($differentType));
        $this->assertFalse($base->equals($differentId));
        $this->assertFalse($base->equals($differentIdAndType));
    }
}
