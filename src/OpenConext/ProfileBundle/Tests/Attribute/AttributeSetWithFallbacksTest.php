<?php

/**
 * Copyright 2016 SURFnet B.V.
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

use OpenConext\ProfileBundle\Attribute\AttributeSetWithFallbacks;
use PHPUnit\Framework\TestCase;
use SAML2\Assertion;
use Surfnet\SamlBundle\SAML2\Attribute\Attribute;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDefinition;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDictionary;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSet;
use Surfnet\SamlBundle\SAML2\Attribute\ConfigurableAttributeSetFactory;
use Surfnet\SamlBundle\SAML2\Response\AssertionAdapter;

class AttributeSetWithFallbacksTest extends TestCase
{
    /**
     * @test
     * @group AttributeSet
     * @group AttributeDictionary
     * @group AssertionAdapter
     */
    public function attribute_set_with_fallbacks_can_be_configured_when_creating_an_assertion_adapter(): void
    {
        $assertion = $this->createMock(Assertion::class);
        $assertion->method('getAttributes')->willReturn([]);

        $dictionary = new AttributeDictionary();

        ConfigurableAttributeSetFactory::configureWhichAttributeSetToCreate(AttributeSetWithFallbacks::class);

        $adapter          = new AssertionAdapter($assertion, $dictionary);
        $attributeSet     = $adapter->getAttributeSet();

        ConfigurableAttributeSetFactory::configureWhichAttributeSetToCreate(AttributeSet::class);

        $this->assertInstanceOf(AttributeSetWithFallbacks::class, $attributeSet);
    }

    /**
     * @test
     * @group AttributeSet
     * @group AttributeDictionary
     */
    public function attribute_set_with_fallbacks_contains_an_attribute_with_an_existing_definition(): void
    {
        $attributeMaceUrn = 'urn:mace:some-attribute';
        $attributeValue   = ['someValue'];

        $attributeDefinition = new AttributeDefinition('some-attribute', $attributeMaceUrn);

        $assertion = $this->createMock(Assertion::class);
        $assertion->method('getAttributes')->willReturn([
            $attributeMaceUrn => $attributeValue
        ]);
        $dictionary = new AttributeDictionary();
        $dictionary->addAttributeDefinition($attributeDefinition);

        $attributeSet = AttributeSetWithFallbacks::createFrom($assertion, $dictionary);

        $attributeIsInSet = $attributeSet->contains(new Attribute($attributeDefinition, $attributeValue));

        $this->assertTrue($attributeIsInSet);
    }


    /**
     * @test
     * @group AttributeSet
     * @group AttributeDictionary
     */
    public function attribute_set_with_fallbacks_falls_back_to_the_the_received_urn_when_encountering_an_undefined_attribute(
    ): void
    {
        $undefinedAttributeUrn = 'urn:mace:not-defined';
        $attributeValue        = ['some-value'];

        $assertion = $this->createMock(Assertion::class);
        $assertion->method('getAttributes')->willReturn([
            $undefinedAttributeUrn => $attributeValue
        ]);

        $dictionary = new AttributeDictionary();

        $attributeSet = AttributeSetWithFallbacks::createFrom($assertion, $dictionary);

        $expectedAttribute = new Attribute(
            new AttributeDefinition(
                $undefinedAttributeUrn,
                $undefinedAttributeUrn,
                $undefinedAttributeUrn
            ),
            $attributeValue
        );

        $attributeIsInSet = $attributeSet->contains($expectedAttribute);

        $this->assertTrue($attributeIsInSet);
    }
}
