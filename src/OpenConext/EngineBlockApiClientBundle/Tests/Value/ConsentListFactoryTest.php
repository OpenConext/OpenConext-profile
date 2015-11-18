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

namespace OpenConext\EngineBlockApiClientBundle\Tests\Value;

use DateTimeImmutable;
use OpenConext\EngineBlockApiClientBundle\Value\ConsentListFactory;
use OpenConext\Profile\Value\Consent;
use OpenConext\Profile\Value\Consent\ServiceProvider;
use OpenConext\Profile\Value\ConsentList;
use OpenConext\Profile\Value\DisplayName;
use OpenConext\Profile\Value\EmailAddress;
use OpenConext\Profile\Value\Entity;
use OpenConext\Profile\Value\EntityId;
use OpenConext\Profile\Value\EntityType;
use OpenConext\Profile\Value\Url;
use PHPUnit_Framework_TestCase as TestCase;

final class ConsentListFactoryTest extends TestCase
{
    /**
     * @test
     * @group Consent
     */
    public function it_can_create_a_consent_list()
    {
        $firstEntityId      = 'http://profile.vm.openconext.invalid/authentication/metadata';
        $secondEntityId     = 'https://serviceregistry.vm.openconext.invalid/simplesaml/module.php/saml/sp/metadata.php/default-sp';
        $firstEula          = 'https://domain.invalid';
        $secondSupportEmail = 'support@openconext.org';

        $given = [
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

        $expectedConsentList = new ConsentList([
            new Consent(
                new ServiceProvider(
                    new Entity(new EntityId($firstEntityId), EntityType::SP()),
                    new DisplayName(['nl' => '', 'en' => '']),
                    new Url($firstEula),
                    null
                ),
                new DateTimeImmutable('2015-11-05T08:43:01+01:00'),
                new DateTimeImmutable('2015-11-05T08:43:01+01:00')
            ),
            new Consent(
                new ServiceProvider(
                    new Entity(new EntityId($secondEntityId), EntityType::SP()),
                    new DisplayName(['nl' => 'OpenConext ServiceRegistry', 'en' => 'OpenConext ServiceRegistry']),
                    null,
                    new EmailAddress($secondSupportEmail)
                ),
                new DateTimeImmutable('2015-11-05T08:17:04+01:00'),
                new DateTimeImmutable('2015-11-05T08:17:04+01:00')
            ),
        ]);

        $this->assertEquals($expectedConsentList, ConsentListFactory::create($given));
    }
}
