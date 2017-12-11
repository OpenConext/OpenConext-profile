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
     * @param Consent $consent
     * @param AttributeSet $releasedAttributes
     * @return SpecifiedConsent
     */
    public static function specifies(Consent $consent, AttributeSet $releasedAttributes)
    {
        return new self($consent, $releasedAttributes);
    }

    /**
     * @param Consent $consent
     * @param AttributeSet $releasedAttributes
     */
    private function __construct(Consent $consent, AttributeSet $releasedAttributes)
    {
        $this->consent            = $consent;
        $this->releasedAttributes = $releasedAttributes;
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

        return count($sources) > 1;
    }

    /**
     * Groups the released attributes on the group they originate from. This can be used to show the attributes.
     *
     * @return Attribute[]
     */
    public function getReleasedAttributesGroupedBySource()
    {
        $grouped = [];
        foreach ($this->getReleasedAttributes() as $attribute) {
            // The source is the same for all possible attribute values, so use the first one.
            $source = $attribute->getValue()[0]['source'];
            $grouped[$source][] = $attribute;
        }
        return $grouped;
    }
}
