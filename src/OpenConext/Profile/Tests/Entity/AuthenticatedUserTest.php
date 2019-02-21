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

namespace OpenConext\Profile\Tests\Entity;

use Mockery as m;
use OpenConext\Profile\Entity\AuthenticatedUser;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use SAML2\Assertion;
use SAML2\Compat\ContainerSingleton;
use SAML2\DOMDocumentFactory;
use SAML2\Exception\RuntimeException;
use Surfnet\SamlBundle\SAML2\Attribute\Attribute;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDefinition;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDictionary;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSet;
use Surfnet\SamlBundle\SAML2\BridgeContainer;
use Surfnet\SamlBundle\SAML2\Response\AssertionAdapter;

class AuthenticatedUserTest extends TestCase
{
    /**
     * We need to set the correct SAML2 Container Singleton for SAML2 to work.
     */
    public function setUp()
    {
        ContainerSingleton::setContainer(new BridgeContainer(new NullLogger()));
    }

    /**
     * @test
     * @group Authentication
     * @group Attributes
     */
    public function no_attributes_are_set_if_no_attributes_are_given_when_creating_an_authenticated_user()
    {
        $emptyAttributeSet = AttributeSet::create([]);

        $assertionAdapter = $this->mockAssertionAdapterWith($emptyAttributeSet, 'test NameID');

        $authenticatedUser  = AuthenticatedUser::createFrom($assertionAdapter, []);
        $actualAttributeSet = $authenticatedUser->getAttributes();

        $this->assertEquals($emptyAttributeSet, $actualAttributeSet);
    }

    /**
     * @test
     * @group Authentication
     * @group Attributes
     */
    public function attributes_are_set_with_given_attributes_when_creating_an_authenticated_user()
    {
        $expectedAttributeSet = AttributeSet::create([
            new Attribute(
                new AttributeDefinition('displayName', 'urn:mace:dir:attribute-def:displayName'),
                ['Chuck', 'Tester']
            ),
            new Attribute(
                new AttributeDefinition('commonName', 'urn:mace:dir:attribute-def:cn'),
                ['Chuck Tester']
            )
        ]);

        $assertionAdapter = $this->mockAssertionAdapterWith($expectedAttributeSet, 'test NameID');

        $authenticatedUser  = AuthenticatedUser::createFrom($assertionAdapter, []);
        $actualAttributeSet = $authenticatedUser->getAttributes();

        $this->assertEquals($expectedAttributeSet, $actualAttributeSet);
    }

    /**
     * @test
     * @group Authentication
     * @group Attributes
     */
    public function attributes_are_filtered_when_creating_an_authenticated_user()
    {
        $expectedAttributeSet = AttributeSet::create([
            new Attribute(
                new AttributeDefinition('displayName', 'urn:mace:dir:attribute-def:displayName'),
                ['Chuck', 'Tester']
            ),
            new Attribute(
                new AttributeDefinition('commonName', 'urn:mace:dir:attribute-def:cn'),
                ['Chuck Tester']
            )
        ]);

        $attributeSet = AttributeSet::create([
            new Attribute(
                new AttributeDefinition('displayName', 'urn:mace:dir:attribute-def:displayName'),
                ['Chuck', 'Tester']
            ),
            new Attribute(
                new AttributeDefinition('commonName', 'urn:mace:dir:attribute-def:cn'),
                ['Chuck Tester']
            ),
            new Attribute(
                new AttributeDefinition('LDAP Directory string', '', 'urn:oid:1.3.6.1.4.1.1466.115.121.1.15'),
                ['testers/chuck1']
            )
        ]);

        $assertionAdapter = $this->mockAssertionAdapterWith($attributeSet, 'test NameID');

        $authenticatedUser  = AuthenticatedUser::createFrom($assertionAdapter, []);
        $actualAttributeSet = $authenticatedUser->getAttributesFiltered();

        $this->assertCount(2, $actualAttributeSet);
        $this->assertEquals($expectedAttributeSet, $actualAttributeSet);
    }

    /**
     * @test
     * @group Authentication
     * @group Attributes
     */
    public function epti_attribute_is_correctly_set_when_creating_an_authenticated_user()
    {
        $expectedAttributeSet = AttributeSet::create([
            new Attribute(
                new AttributeDefinition('eduPersonTargetedID', 'urn:mace:dir:attribute-def:eduPersonTargetedID'),
                ['abcd-some-value-xyz']
            ),
            new Attribute(
                new AttributeDefinition('displayName', 'urn:mace:dir:attribute-def:displayName'),
                ['Tester']
            ),
        ]);

        $assertionWithEpti   = $this->getAssertionWithEpti();
        $attributeDictionary = $this->getAttributeDictionary();

        $assertionAdapter  = $this->mockAssertionAdapterWith(
            AttributeSet::createFrom($assertionWithEpti, $attributeDictionary),
            'abcd-some-value-xyz'
        );

        $authenticatedUser  = AuthenticatedUser::createFrom($assertionAdapter, []);
        $actualAttributeSet = $authenticatedUser->getAttributes();

        $this->assertEquals($expectedAttributeSet, $actualAttributeSet);
    }

    private function mockAssertionAdapterWith(AttributeSet $attributeSet, $nameId)
    {
        $assertionAdapter = m::mock(AssertionAdapter::class);
        $assertionAdapter
            ->shouldReceive('getAttributeSet')
            ->andReturn($attributeSet);

        $assertionAdapter
            ->shouldReceive('getNameID')
            ->andReturn($nameId);

        return $assertionAdapter;
    }

    private function getAttributeDictionary()
    {
        $attributeDictionary = new AttributeDictionary();
        $attributeDictionary->addAttributeDefinition(
            new AttributeDefinition('displayName', 'urn:mace:dir:attribute-def:displayName')
        );
        $attributeDictionary->addAttributeDefinition(
            new AttributeDefinition('eduPersonTargetedID', 'urn:mace:dir:attribute-def:eduPersonTargetedID')
        );

        return $attributeDictionary;
    }

    private function getAssertionWithEpti()
    {
        $xml = <<<XML
<saml:Assertion
        xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion"
        xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol"
        xmlns:xs="http://www.w3.org/2001/XMLSchema"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        Version="2.0"
        ID="_93af655219464fb403b34436cfb0c5cb1d9a5502"
        IssueInstant="1970-01-01T01:33:31Z">
    <saml:Issuer>Provider</saml:Issuer>
      <saml:AttributeStatement>
         <saml:Attribute Name="urn:mace:dir:attribute-def:eduPersonTargetedID"
            NameFormat="urn:oasis:names:tc:SAML:2.0:attrname-format:uri">
            <saml:AttributeValue><saml:NameID Format="urn:oasis:names:tc:SAML:2.0:nameid-format:transient">abcd-some-value-xyz</saml:NameID></saml:AttributeValue>
        </saml:Attribute>
        <saml:Attribute Name="urn:mace:dir:attribute-def:displayName" NameFormat="urn:oasis:names:tc:SAML:2.0:attrname-format:uri">
            <saml:AttributeValue xsi:type="xs:string">Tester</saml:AttributeValue>
         </saml:Attribute>
      </saml:AttributeStatement>
   </saml:Assertion>
XML;

        return new Assertion(DOMDocumentFactory::fromString($xml)->firstChild);
    }
}
