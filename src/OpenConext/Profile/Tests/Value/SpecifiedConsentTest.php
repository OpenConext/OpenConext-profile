<?php

declare(strict_types = 1);

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

namespace OpenConext\Profile\Tests\Value;

use DateTimeImmutable;
use OpenConext\Profile\Value\Arp;
use OpenConext\Profile\Value\Consent;
use OpenConext\Profile\Value\Consent\ServiceProvider;
use OpenConext\Profile\Value\ConsentType;
use OpenConext\Profile\Value\ContactEmailAddress;
use OpenConext\Profile\Value\DisplayName;
use OpenConext\Profile\Value\Entity;
use OpenConext\Profile\Value\EntityId;
use OpenConext\Profile\Value\EntityType;
use OpenConext\Profile\Value\NameIdFormat;
use OpenConext\Profile\Value\SpecifiedConsent;
use OpenConext\Profile\Value\Url;
use OpenConext\ProfileBundle\Attribute\AttributeSetWithFallbacks;
use PHPUnit\Framework\TestCase;
use Surfnet\SamlBundle\SAML2\Attribute\Attribute;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDefinition;

class SpecifiedConsentTest extends TestCase
{
    public function test_it_is_aware_of_multiple_attribute_sources(): void
    {
        $someAttributeDefinition = new AttributeDefinition(
            'someAttribute',
            'urn:mace:some-attribute',
            'urn:oid:0.0.0.0.0.1',
        );
        $anotherAttributeDefinition = new AttributeDefinition('anotherAttribute', null, 'urn:oid:0.0.0.0.0.2');

        $someConsent = new Consent(
            new ServiceProvider(
                new Entity(
                    new EntityId('some-entity-id'),
                    EntityType::SP(),
                ),
                new DisplayName([
                    'en' => 'Some display name'
                ]),
                new DisplayName([
                    'en' => 'Some organization display name'
                ]),
                new NameIdFormat(''),
                new Url('http://some-eula-url.example'),
                new ContactEmailAddress('some@email.example'),
            ),
            new DateTimeImmutable(),
            ConsentType::explicit(),
        );

        // The results as expected to have been returned from the API (sources have been added)
        $expectedSomeAttribute    = new Attribute($someAttributeDefinition, [['value' => 'some-value', 'source' => 'idp']]);
        $expectedAnotherAttribute = new Attribute($anotherAttributeDefinition, [['value' => 'another-value', 'source' => 'voot']]);

        $specifiedConsent = SpecifiedConsent::specifies(
            $someConsent,
            AttributeSetWithFallbacks::create([$expectedSomeAttribute, $expectedAnotherAttribute]),
            Arp::createWith([]),
        );

        $this->assertTrue($specifiedConsent->hasMultipleSources());
    }

    public function test_it_is_aware_of_a_single_attribute_source(): void
    {
        $someAttributeDefinition = new AttributeDefinition(
            'someAttribute',
            'urn:mace:some-attribute',
            'urn:oid:0.0.0.0.0.1',
        );
        $anotherAttributeDefinition = new AttributeDefinition('anotherAttribute', null, 'urn:oid:0.0.0.0.0.2');

        $someConsent = new Consent(
            new ServiceProvider(
                new Entity(
                    new EntityId('some-entity-id'),
                    EntityType::SP(),
                ),
                new DisplayName([
                    'en' => 'Some display name'
                ]),
                new DisplayName([
                    'en' => 'Some organization display name'
                ]),
                new NameIdFormat(''),
                new Url('http://some-eula-url.example'),
                new ContactEmailAddress('some@email.example'),
            ),
            new DateTimeImmutable(),
            ConsentType::explicit(),
        );

        // The results as expected to have been returned from the API (sources have been added)
        $expectedSomeAttribute    = new Attribute($someAttributeDefinition, [['value' => 'some-value', 'source' => 'voot']]);
        $expectedAnotherAttribute = new Attribute($anotherAttributeDefinition, [['value' => 'another-value', 'source' => 'voot']]);

        $specifiedConsent = SpecifiedConsent::specifies(
            $someConsent,
            AttributeSetWithFallbacks::create([$expectedSomeAttribute, $expectedAnotherAttribute]),
            Arp::createWith([]),
        );

        $this->assertFalse($specifiedConsent->hasMultipleSources());
    }
    public function test_it_is_aware_of_zero_attribute_sources(): void
    {
        $someConsent = new Consent(
            new ServiceProvider(
                new Entity(
                    new EntityId('some-entity-id'),
                    EntityType::SP(),
                ),
                new DisplayName([
                    'en' => 'Some display name'
                ]),
                new DisplayName([
                    'en' => 'Some organization display name'
                ]),
                new NameIdFormat(''),
                new Url('http://some-eula-url.example'),
                new ContactEmailAddress('some@email.example'),
            ),
            new DateTimeImmutable(),
            ConsentType::explicit(),
        );

        $specifiedConsent = SpecifiedConsent::specifies(
            $someConsent,
            AttributeSetWithFallbacks::create([]),
            Arp::createWith([]),
        );

        $this->assertFalse($specifiedConsent->hasMultipleSources());
    }

    public function test_it_handles_arp_sources(): void
    {
        $someConsent = new Consent(
            new ServiceProvider(
                new Entity(
                    new EntityId('some-entity-id'),
                    EntityType::SP(),
                ),
                new DisplayName([
                    'en' => 'Some display name'
                ]),
                new DisplayName([
                    'en' => 'Some organization display name'
                ]),
                new NameIdFormat(''),
                new Url('http://some-eula-url.example'),
                new ContactEmailAddress('some@email.example'),
            ),
            new DateTimeImmutable(),
            ConsentType::explicit(),
        );

        $arpData = json_decode(file_get_contents(__DIR__ . '/../fixture/arp-response.json'), true);

        $specifiedConsent = SpecifiedConsent::specifies(
            $someConsent,
            AttributeSetWithFallbacks::create([]),
            Arp::createWith($arpData),
        );

        $groupedBySources = $specifiedConsent->getAttributeAggregatedAttributes();

        $this->assertTrue($specifiedConsent->hasMultipleSources());
        $this->assertCount(3, $groupedBySources);
        $this->assertArrayHasKey('sab', $groupedBySources);
        $this->assertArrayHasKey('voot', $groupedBySources);
        $this->assertArrayHasKey('orcid', $groupedBySources);
    }
}
