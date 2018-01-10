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

use DateTimeImmutable;
use Mockery;
use OpenConext\Profile\Exception\InvalidArpDataException;
use OpenConext\Profile\Value\Arp;
use OpenConext\Profile\Value\Consent;
use OpenConext\Profile\Value\Consent\ServiceProvider;
use OpenConext\Profile\Value\ConsentType;
use OpenConext\Profile\Value\ContactEmailAddress;
use OpenConext\Profile\Value\DisplayName;
use OpenConext\Profile\Value\Entity;
use OpenConext\Profile\Value\EntityId;
use OpenConext\Profile\Value\EntityType;
use OpenConext\Profile\Value\SpecifiedConsent;
use OpenConext\Profile\Value\Url;
use OpenConext\ProfileBundle\Attribute\AttributeSetWithFallbacks;
use PHPUnit\Framework\TestCase;
use stdClass;
use Surfnet\SamlBundle\SAML2\Attribute\Attribute;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDefinition;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDictionary;

class ArpTest extends TestCase
{
    public function test_it_can_be_constructed_with_empty_arp_data()
    {
        $arp = Arp::createWith([]);
        $this->assertInstanceOf(Arp::class, $arp);
        $this->assertEmpty($arp->getAttributesGroupedBySource());
    }

    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    /**
     * @expectedException \OpenConext\Profile\Exception\InvalidArpDataException
     * @dataProvider invalidArpData
     */
    public function test_it_rejects_invalid_data_structures($invalidArpData)
    {
        Arp::createWith($invalidArpData);
    }

    public function test_construction_with_valid_arp_data()
    {
        $arp = Arp::createWith(json_decode(file_get_contents(__DIR__ . '/../fixture/arp-response.json'), true));
        $this->assertInstanceOf(Arp::class, $arp);
        $attributes = $arp->getAttributesGroupedBySource();

        $this->assertCount(4, $attributes);
        $this->assertArrayHasKey('idp', $attributes);
        $this->assertArrayHasKey('sab', $attributes);
        $this->assertArrayHasKey('voot', $attributes);
        $this->assertArrayHasKey('orcid', $attributes);

        // All entries should be Attribute instances.
        foreach ($attributes['idp'] as $attribute) {
            $this->assertInstanceOf(Attribute::class, $attribute);
        }
    }

    public function test_filtering_of_idp_sources()
    {
        $arp = Arp::createWith(json_decode(file_get_contents(__DIR__ . '/../fixture/arp-response.json'), true));
        $this->assertArrayNotHasKey('idp', $arp->getNonIdpAttributes());
        $this->assertArrayHasKey('idp', $arp->getAttributesGroupedBySource());

        $arp = Arp::createWith([]);
        $this->assertArrayNotHasKey('idp', $arp->getNonIdpAttributes());
        $this->assertArrayNotHasKey('idp', $arp->getAttributesGroupedBySource());
    }

    public function test_dictionary_usage()
    {
        // The dictionary is mocked and always returns the same definition.
        $dictionary = Mockery::mock(AttributeDictionary::class);
        $bogusDefinition = new AttributeDefinition('Foobar', 'urn.mace.FooBar', '123.323.432.12');
        $dictionary
            ->shouldReceive('getAttributeDefinitionByUrn')
            ->times(13)
            ->andReturn($bogusDefinition);

        $arp = Arp::createWith(
            json_decode(file_get_contents(__DIR__ . '/../fixture/arp-response.json'), true),
            $dictionary
        );

        // All entries should have the same attribute definition
        foreach ($arp->getAttributesGroupedBySource()['idp'] as $attribute) {
            $this->assertEquals($bogusDefinition, $attribute->getAttributeDefinition());
        }
    }

    public function invalidArpData()
    {
        return [
            [[null]],
            [['urn.mace.foobar' => [['invalid-key' => 'foo']]]],
            [['urn.mace.foobar' => [['invalid-key' => null]]]],
            [['urn.mace.foobar' => [['source' => new StdClass()]]]],
            [['urn.mace.foobar' => [['value' => new StdClass()]]]],
            [['urn.mace.foobar' => [['value' => '*', 'sourcy' => 'fff']]]],
            [['urn.mace.foobar' => [['value' => '*', 'source' => []]]]],
            [['urn.mace.foobar' => [['value' => '*'], ['value' => '*', 'source' => []]]]],
        ];
    }
}
