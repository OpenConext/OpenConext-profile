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

namespace OpenConext\ProfileBundle\Tests\Service;

use DateTimeImmutable;
use Mockery;
use OpenConext\EngineBlockApiClient\Http\JsonApiClient;
use OpenConext\EngineBlockApiClient\Service\AttributeReleasePolicyService;
use OpenConext\Profile\Value\Arp;
use OpenConext\Profile\Value\Consent;
use OpenConext\Profile\Value\Consent\ServiceProvider;
use OpenConext\Profile\Value\ConsentList;
use OpenConext\Profile\Value\ConsentType;
use OpenConext\Profile\Value\ContactEmailAddress;
use OpenConext\Profile\Value\DisplayName;
use OpenConext\Profile\Value\Entity;
use OpenConext\Profile\Value\EntityId;
use OpenConext\Profile\Value\EntityType;
use OpenConext\Profile\Value\NameIdFormat;
use OpenConext\Profile\Value\SpecifiedConsent;
use OpenConext\Profile\Value\SpecifiedConsentList;
use OpenConext\Profile\Value\Url;
use OpenConext\ProfileBundle\Attribute\AttributeSetWithFallbacks;
use PHPUnit\Framework\TestCase;
use Surfnet\SamlBundle\SAML2\Attribute\Attribute;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDefinition;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDictionary;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSet;

class AttributeReleasePolicyServiceTest extends TestCase
{
    private const ARP_DATA = [
            'some-entity-id' => [
                'urn:mace:some-attribute' => [
                    [
                        'value' => 'some-value',
                        'source' => 'idp',
                    ],
                ],
                'urn:oid:0.0.0.0.0.1' => [
                    [
                        'value' => 'some-value',
                        'source' => 'idp',
                    ],
                ],
                'urn:oid:0.0.0.0.0.2' => [
                    [
                        'value' => 'another-value',
                        'source' => 'sab',
                    ],
                ],
            ],
            'another-entity-id' => [
                'urn:oid:0.0.0.0.0.2' => [
                    [
                        'value' => 'another-value',
                        'source' => 'voot',
                    ],
                ]
            ],
        ];

    /**
     * @test
     * @group AttributeReleasePolicy
     */
    public function consent_list_and_attributes_are_correctly_converted_to_a_request_and_the_response_is_mapped_correctly_to_a_result(): void
    {
        $someAttributeDefinition = new AttributeDefinition(
            'someAttribute',
            'urn:mace:some-attribute',
            'urn:oid:0.0.0.0.0.1'
        );
        $anotherAttributeDefinition = new AttributeDefinition('anotherAttribute', null, 'urn:oid:0.0.0.0.0.2');

        $attributeDictionary = new AttributeDictionary();
        $attributeDictionary->addAttributeDefinition($someAttributeDefinition);
        $attributeDictionary->addAttributeDefinition($anotherAttributeDefinition);

        $client = Mockery::mock(JsonApiClient::class);
        $arpService = new AttributeReleasePolicyService($client, $attributeDictionary);

        $client->expects('post')
            ->with(
                    [
                        'entityIds'  => [
                            'some-entity-id',
                            'another-entity-id'
                        ],
                        'attributes' => [
                            'urn:mace:some-attribute' => ['some-value'],
                            'urn:oid:0.0.0.0.0.1' => ['some-value'],
                            'urn:oid:0.0.0.0.0.2' => ['another-value'],
                        ],
                        'showSources' => true
                    ],
                    '/arp',
            )
            ->andReturn([
                'some-entity-id' => [
                    'urn:mace:some-attribute' => [
                        [
                            'value' => 'some-value',
                            'source' => 'idp',
                        ],
                    ],
                    'urn:oid:0.0.0.0.0.1' => [
                        [
                            'value' => 'some-value',
                            'source' => 'idp',
                        ],
                    ],
                    'urn:oid:0.0.0.0.0.2' => [
                        [
                            'value' => 'another-value',
                            'source' => 'idp',
                        ],
                    ],
                ],
                'another-entity-id' => [
                    'urn:oid:0.0.0.0.0.2' => [
                        [
                            'value' => 'another-value',
                            'source' => 'idp',
                        ],
                    ]
                ],
            ]);

        $client->expects('post')
            ->with(
                    [
                        'entityIds' => [
                            'some-entity-id',
                            'another-entity-id',
                        ]
                    ],
                    '/read-arp',
            )
            ->andReturn(self::ARP_DATA);

        $someConsent = new Consent(
            new ServiceProvider(
                new Entity(
                    new EntityId('some-entity-id'),
                    EntityType::SP()
                ),
                new DisplayName([
                    'en' => 'Some display name'
                ]),
                new DisplayName([
                    'en' => 'Some organization display name'
                ]),
                new NameIdFormat(''),
                new Url('http://some-eula-url.example'),
                new ContactEmailAddress('some@email.example')
            ),
            new DateTimeImmutable(),
            ConsentType::explicit()
        );
        $anotherConsent = new Consent(
            new ServiceProvider(
                new Entity(
                    new EntityId('another-entity-id'),
                    EntityType::SP()
                ),
                new DisplayName([
                    'en' =>'Another display name'
                ]),
                new DisplayName([
                    'en' => 'Some organization display name'
                ]),
                new NameIdFormat(''),
                new Url('http://another-eula-url.example'),
                new ContactEmailAddress('another@email.example')
            ),
            new DateTimeImmutable(),
            ConsentType::explicit()
        );
        $consentList  = new ConsentList([$someConsent, $anotherConsent]);

        $someAttribute    = new Attribute($someAttributeDefinition, ['some-value']);
        $anotherAttribute = new Attribute($anotherAttributeDefinition, ['another-value']);

        // The results as expected to have been returned from the API (sources have been added)
        $expectedSomeAttribute    = new Attribute($someAttributeDefinition, [['value' => 'some-value', 'source' => 'idp']]);
        $expectedAnotherAttribute = new Attribute($anotherAttributeDefinition, [['value' => 'another-value', 'source' => 'idp']]);
        $attributeSet     = AttributeSet::create([$someAttribute, $anotherAttribute,]);

        $expectedResult = SpecifiedConsentList::createWith([
            SpecifiedConsent::specifies(
                $someConsent,
                AttributeSetWithFallbacks::create([$expectedSomeAttribute, $expectedAnotherAttribute]),
                Arp::createWith(self::ARP_DATA['some-entity-id'], $attributeDictionary)
            ),
            SpecifiedConsent::specifies(
                $anotherConsent,
                AttributeSetWithFallbacks::create([$expectedAnotherAttribute]),
                Arp::createWith(self::ARP_DATA['another-entity-id'], $attributeDictionary)
            )
        ]);

        $result = $arpService->applyAttributeReleasePolicies($consentList, $attributeSet);

        $this->assertEquals($expectedResult, $result);
    }
}
