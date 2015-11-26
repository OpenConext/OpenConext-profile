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

namespace OpenConext\ProfileBundle\Tests\Consent;

use OpenConext\ProfileBundle\Consent\BlacklistFilter;
use PHPUnit_Framework_TestCase as TestCase;
use Surfnet\SamlBundle\SAML2\Attribute\Attribute;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDefinition;

class BlacklistFilterTest extends TestCase
{
    /**
     * @test
     * @group AttributeFilter
     * @group Consent
     *
     * @dataProvider blacklistedUrnProvider
     */
    public function blacklisted_urn_oids_are_not_allowed($blacklistedUrn)
    {
        $blacklistFilter = new BlacklistFilter();
        $attribute = new Attribute(new AttributeDefinition('blacklisted', null, $blacklistedUrn), ['attributeValue']);

        $isAllowed = $blacklistFilter->allows($attribute);

        $this->assertFalse(
            $isAllowed,
            sprintf('BlacklistFilter allows blacklisted attribute with oid "%s"', $blacklistedUrn)
        );
    }

    /**
     * @test
     * @group AttributeFilter
     * @group Consent
     *
     * @dataProvider blacklistedUrnProvider
     */
    public function blacklisted_urn_maces_are_not_allowed($blacklistedUrn)
    {
        $blacklistFilter = new BlacklistFilter();
        $attribute = new Attribute(new AttributeDefinition('blacklisted', $blacklistedUrn), ['attributeValue']);

        $isAllowed = $blacklistFilter->allows($attribute);

        $this->assertFalse(
            $isAllowed,
            sprintf('BlacklistFilter allows blacklisted attribute with mace "%s"', $blacklistedUrn)
        );
    }

    /**
     * @test
     * @group AttributeFilter
     * @group Consent
     */
    public function non_blacklisted_urn_maces_are_allowed()
    {
        $blacklistFilter = new BlacklistFilter();
        $allowedUrn = 'urn:allowed';
        $attribute = new Attribute(new AttributeDefinition('not-blacklisted', $allowedUrn), ['attributeValue']);

        $isAllowed = $blacklistFilter->allows($attribute);

        $this->assertTrue(
            $isAllowed,
            sprintf('BlacklistFilter does not allow blacklisted attribute with mace "%s"', $allowedUrn)
        );
    }

    /**
     * @test
     * @group AttributeFilter
     * @group Consent
     */
    public function non_blacklisted_urn_oids_are_allowed()
    {
        $blacklistFilter = new BlacklistFilter();
        $allowedUrn = 'urn:allowed';
        $attribute = new Attribute(new AttributeDefinition('not-blacklisted', null, $allowedUrn), ['attributeValue']);

        $isAllowed = $blacklistFilter->allows($attribute);

        $this->assertTrue(
            $isAllowed,
            sprintf('BlacklistFilter does not allow blacklisted attribute with mace "%s"', $allowedUrn)
        );
    }

    public function blacklistedUrnProvider()
    {
        return array_map(function($urn) {
            return [$urn];
        }, BlacklistFilter::BLACKLIST);
    }
}
