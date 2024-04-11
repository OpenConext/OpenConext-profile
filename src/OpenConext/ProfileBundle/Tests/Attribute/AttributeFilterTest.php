<?php

/**
 * Copyright 2017 SURFnet B.V.
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

namespace OpenConext\ProfileBundle\Tests\Attribute;

use OpenConext\ProfileBundle\Attribute\AttributeFilter;
use PHPUnit\Framework\TestCase;
use Surfnet\SamlBundle\SAML2\Attribute\Attribute;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDefinition;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSet;

class AttributeFilterTest extends TestCase
{
    public function test_it_handles_empty_set(): void
    {
        $filter = new AttributeFilter();
        $set = AttributeSet::create([]);
        $filterResult = $filter->filter($set);

        $this->assertInstanceOf(AttributeSet::class, $filterResult);
        $this->assertEquals($set->count(), $filterResult->count());
    }

    public function test_it_filters_inappropriate_attributes(): void
    {
        $filter = new AttributeFilter();

        $commonName = $this->getMockAttribute('commonName');
        $uid = $this->getMockAttribute('uid');
        $unwanted = $this->getMockAttribute('jibberJabber');

        $set = AttributeSet::create([$commonName, $uid, $unwanted]);
        $filterResult = $filter->filter($set);

        $this->assertInstanceOf(AttributeSet::class, $filterResult);
        $this->assertEquals(2, $filterResult->count());
    }

    private function getMockAttribute(string $attributeName)
    {
        $mockAttribute = $this->createMock(Attribute::class);
        $attributeDefinition = $this->createMock(AttributeDefinition::class);

        $mockAttribute->method('equals')->willReturn(false);
        $mockAttribute->method('getAttributeDefinition')->willReturn($attributeDefinition);
        $attributeDefinition->method('getName')->willReturn($attributeName);

        return $mockAttribute;
    }
}
