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

namespace OpenConext\EngineBlockApiClientBundle\Tests\Serialisation;

use DateTimeImmutable;
use OpenConext\EngineBlockApiClientBundle\Serialisation\ConsentListDeserialiser;
use OpenConext\Profile\Value\Consent;
use OpenConext\Profile\Value\ConsentList;
use OpenConext\Profile\Value\Entity;
use OpenConext\Profile\Value\EntityId;
use OpenConext\Profile\Value\EntityType;
use PHPUnit_Framework_TestCase as TestCase;

final class ConsentListDeserialiserTest extends TestCase
{
    /**
     * @test
     * @group consent
     * @group deserialisation
     */
    public function it_can_deserialise_a_consent_list()
    {
        $firstEntityId      = 'http://profile.vm.openconext.invalid/authentication/metadata';
        $secondEntityId     = 'https://serviceregistry.vm.openconext.invalid/simplesaml/module.php/saml/sp/metadata.php/default-sp';
        $firstEula          = 'https://domain.invalid';
        $secondSupportEmail = 'support@openconext.org';

        $json = [
            [
                'service_provider' => [
                    'entity_id'     => $firstEntityId,
                    'display_name'  => ['en' => '', 'nl' => '',],
                    'eula_url'      => $firstEula,
                    'support_email' => null,
                ],
                'consent_given_on' => '2015-11-05T08:43:01+01:00',
                'last_used_on'     => '2015-11-05T08:43:01+01:00',
            ],
            [
                'service_provider' => [
                    'entity_id'     => $secondEntityId,
                    'display_name'  => ['en' => 'OpenConext ServiceRegistry', 'nl' => 'OpenConext ServiceRegistry'],
                    'eula_url'      => null,
                    'support_email' => $secondSupportEmail,
                ],
                'consent_given_on' => '2015-11-05T08:17:04+01:00',
                'last_used_on'     => '2015-11-05T08:17:04+01:00',
            ],
        ];
        $consentList = ConsentListDeserialiser::fromJson($json);

        $this->assertInstanceOf(ConsentList::class, $consentList);
        $this->assertCount(2, $consentList);

        /** @var Consent[] $consents */
        $consents = iterator_to_array($consentList);

        $consent = $consents[0];
        $serviceProvider = $consent->getServiceProvider();
        $this->assertEquals(new DateTimeImmutable('2015-11-05T08:43:01+01:00'), $consent->getConsentGivenOn());
        $this->assertEquals(new DateTimeImmutable('2015-11-05T08:43:01+01:00'), $consent->getLastUsedOn());
        $this->assertTrue(
            $serviceProvider->getEntity()->equals(new Entity(new EntityId($firstEntityId), EntityType::SP())),
            'Entity of first consent is different than expected'
        );
        $this->assertFalse($serviceProvider->getDisplayName()->hasFilledTranslationForLocale('en'), "First consent's SP shouldn't have a filled English translation");
        $this->assertFalse($serviceProvider->getDisplayName()->hasFilledTranslationForLocale('nl'), "First consent's SP shouldn't have a filled Dutch translation");
        $this->assertEquals($firstEula, (string) $serviceProvider->getEulaUrl());
        $this->assertFalse($serviceProvider->hasSupportEmail(), "First consent's service provider should not have a support e-mail");

        $consent = $consents[1];
        $serviceProvider = $consent->getServiceProvider();
        $this->assertEquals(new DateTimeImmutable('2015-11-05T08:17:04+01:00'), $consents[1]->getConsentGivenOn());
        $this->assertEquals(new DateTimeImmutable('2015-11-05T08:17:04+01:00'), $consents[1]->getLastUsedOn());
        $this->assertTrue(
            $serviceProvider->getEntity()->equals(new Entity(new EntityId($secondEntityId), EntityType::SP())),
            'Entity of second consent is different than expected'
        );
        $this->assertTrue($serviceProvider->getDisplayName()->hasFilledTranslationForLocale('en'), 'Second consent must have English translation');
        $this->assertTrue($serviceProvider->getDisplayName()->hasFilledTranslationForLocale('nl'), 'Second consent must have Dutch translation');
        $this->assertFalse($serviceProvider->hasEulaUrl(), "Second consent's SP shouldn't have a EULA URL");
        $this->assertTrue($serviceProvider->hasSupportEmail(), "Second consent's SP should have a support e-mail");
        $this->assertEquals($secondSupportEmail, (string) $serviceProvider->getSupportEmail());
    }
}
