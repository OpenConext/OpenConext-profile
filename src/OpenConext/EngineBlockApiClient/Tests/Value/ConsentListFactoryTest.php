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

namespace OpenConext\EngineBlockApiClient\Tests\Value;

use DateTimeImmutable;
use OpenConext\EngineBlockApiClient\Value\ConsentListFactory;
use OpenConext\Profile\Value\Consent;
use OpenConext\Profile\Value\Consent\ServiceProvider;
use OpenConext\Profile\Value\ConsentList;
use OpenConext\Profile\Value\ConsentType;
use OpenConext\Profile\Value\DisplayName;
use OpenConext\Profile\Value\ContactEmailAddress;
use OpenConext\Profile\Value\Entity;
use OpenConext\Profile\Value\EntityId;
use OpenConext\Profile\Value\EntityType;
use OpenConext\Profile\Value\NameIdFormat;
use OpenConext\Profile\Value\Url;
use PHPUnit\Framework\TestCase;

final class ConsentListFactoryTest extends TestCase
{
    /**
     * @test
     * @group Consent
     */
    public function it_can_create_a_consent_list(): void
    {
        $firstEntityId      = 'http://profile.vm.openconext.invalid/authentication/metadata';
        $secondEntityId     = 'https://serviceregistry.vm.openconext.invalid/simplesaml/module.php/saml/sp/metadata.php/default-sp';
        $firstEula          = 'https://domain.invalid';
        $secondSupportEmail = 'support@openconext.org';

        $givenFirstConsentTypeValue = 'explicit';
        $expectedFirstConsentType = ConsentType::explicit();
        $givenSecondConsentTypeValue = 'implicit';
        $expectedSecondConsentType = ConsentType::implicit();

        $given = [
            [
                'service_provider' => [
                    'entity_id'     => $firstEntityId,
                    'display_name'  => ['en' => '', 'nl' => '',],
                    'organization_display_name'  => ['en' => 'OpenConext', 'nl' => 'OpenConext'],
                    'support_url'  => ['en' => 'https://example.org/support-en', 'nl' => 'https://example.org/support-nl'],
                    'eula_url'      => $firstEula,
                    'support_email' => null,
                    'name_id_format' => 'test',
                ],
                'consent_given_on' => '2015-11-05T08:43:01+01:00',
                'consent_type'     => $givenFirstConsentTypeValue
            ],
            [
                'service_provider' => [
                    'entity_id'     => $secondEntityId,
                    'display_name'  => ['en' => 'OpenConext ServiceRegistry', 'nl' => 'OpenConext ServiceRegistry'],
                    'organization_display_name'  => ['en' => 'OpenConext', 'nl' => 'OpenConext'],
                    'support_url'  => ['en' => 'https://example.org/support-en', 'nl' => 'https://example.org/support-nl'],
                    'eula_url'      => null,
                    'support_email' => $secondSupportEmail,
                    'name_id_format' => 'test',
                ],
                'consent_given_on' => '2015-11-05T08:17:04+01:00',
                'consent_type'     => $givenSecondConsentTypeValue
            ],
        ];

        $expectedConsentList = new ConsentList([
            new Consent(
                new ServiceProvider(
                    new Entity(new EntityId($firstEntityId), EntityType::SP()),
                    new DisplayName(['nl' => '', 'en' => '']),
                    new DisplayName(['nl' => 'OpenConext', 'en' => 'OpenConext']),
                    new NameIdFormat('test'),
                    new Url($firstEula),
                    null,
                    new Url('https://example.org/support-en'),
                    new Url('https://example.org/support-nl')
                ),
                new DateTimeImmutable('2015-11-05T08:43:01+01:00'),
                $expectedFirstConsentType
            ),
            new Consent(
                new ServiceProvider(
                    new Entity(new EntityId($secondEntityId), EntityType::SP()),
                    new DisplayName(['nl' => 'OpenConext ServiceRegistry', 'en' => 'OpenConext ServiceRegistry']),
                    new DisplayName(['nl' => 'OpenConext', 'en' => 'OpenConext']),
                    new NameIdFormat('test'),
                    null,
                    new ContactEmailAddress($secondSupportEmail),
                    new Url('https://example.org/support-en'),
                    new Url('https://example.org/support-nl')
                ),
                new DateTimeImmutable('2015-11-05T08:17:04+01:00'),
                $expectedSecondConsentType
            ),
        ]);

        $this->assertEquals($expectedConsentList, ConsentListFactory::createListFromMetadata($given));
    }
}
