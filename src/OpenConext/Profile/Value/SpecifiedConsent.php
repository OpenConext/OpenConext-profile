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

namespace OpenConext\Profile\Value;

use OpenConext\Profile\Value\Consent\ServiceProvider;
use Surfnet\SamlBundle\SAML2\Attribute\Attribute;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSet;

class SpecifiedConsent
{
    /**
     * @var Consent
     */
    private $consent;

    /**
     * @var AttributeSet
     */
    private $releasedAttributes;

    /**
     * @var Arp
     */
    private $arp;

    /**
     * @param Consent $consent
     * @param AttributeSet $releasedAttributes
     * @param Arp $arp
     * @return SpecifiedConsent
     */
    public static function specifies(Consent $consent, AttributeSet $releasedAttributes, Arp $arp)
    {
        return new self($consent, $releasedAttributes, $arp);
    }

    /**
     * @param Consent $consent
     * @param AttributeSet $releasedAttributes
     * @param Arp $arp
     */
    private function __construct(Consent $consent, AttributeSet $releasedAttributes, Arp $arp)
    {
        $this->consent = $consent;
        $this->releasedAttributes = $releasedAttributes;
        $this->arp = $arp;
    }

    /**
     * @return Consent
     */
    public function getConsent()
    {
        return $this->consent;
    }

    /**
     * @return AttributeSet
     */
    public function getReleasedAttributes()
    {
        return $this->releasedAttributes;
    }

    public function hasMultipleSources()
    {
        $sources = [];
        foreach ($this->getReleasedAttributes() as $attribute) {
            $source = $attribute->getValue()[0]['source'];
            $sources[$source] = $source;
        }

        $sources = array_merge($sources, $this->arp->getNonIdpAttributes());

        return count($sources) > 1;
    }

    /**
     * Groups the released attributes on the group they originate from. This can be used to show the AA attributes.
     * Note that the attributes from the IdP source are omitted in the results.
     *
     * @return Attribute[]
     */
    public function getReleasedAttributesGroupedBySource()
    {
        $grouped = [];
        foreach ($this->getReleasedAttributes() as $attribute) {
            // The source is the same for all possible attribute values, so use the first one.
            $source = $attribute->getValue()[0]['source'];
            if ($source !== 'idp') {
                continue;
            }

            $grouped[$source][] = $attribute;
        }
        $grouped = array_merge($grouped, $this->arp->getNonIdpAttributes());

        return $grouped;
    }

    /**
     * @return bool
     */
    public function hasMotivations()
    {
        return $this->arp->hasMotivations();
    }

    public function getMotivation(Attribute $attribute)
    {
        return $this->arp->getMotivationFor($attribute);
    }

    public function getEduPersonTargetedID(): string
    {
        foreach ($this->getReleasedAttributes() as $attribute) {
            $attributeName = $attribute->getAttributeDefinition()->getName();

            if ($attributeName === 'eduPersonTargetedID') {
                return $attribute->getValue()[0]['value'];
            }
        }

        return '';
    }

    public function getServiceProvider(): ServiceProvider
    {
        return $this->consent->getServiceProvider();
    }
}
