<?php

declare(strict_types = 1);

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

use OpenConext\Profile\Value\Consent;
use OpenConext\Profile\Value\Consent\ServiceProvider;
use Surfnet\SamlBundle\SAML2\Attribute\Attribute;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSet;

class SpecifiedConsent
{
    public static function specifies(
        Consent $consent,
        AttributeSet $releasedAttributes,
        Arp $arp,
    ): self {
        return new self($consent, $releasedAttributes, $arp);
    }

    private function __construct(
        private readonly Consent $consent,
        private readonly AttributeSet $releasedAttributes,
        private readonly Arp $arp,
    ) {
    }

    /**
     * @return Consent
     */
    public function getConsent(): Consent
    {
        return $this->consent;
    }

    /**
     * @return AttributeSet
     */
    public function getReleasedAttributes(): AttributeSet
    {
        return $this->releasedAttributes;
    }

    public function hasMultipleSources(): bool
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
     * Groups the released attributes on the group they originate from. This
     * can be used to show the AA attributes.
     * Note that the attributes from the IdP source are omitted in the results.
     *
     * @return array<string, array<int, Attribute>>
     */
    public function getIdPAttributes(): array
    {
        $grouped = [];
        foreach ($this->getReleasedAttributes() as $attribute) {
            assert($attribute instanceof Attribute);
            // The source is the same for all possible attribute values, so use the first one.
            $source = $attribute->getValue()[0]['source'];
            if ($source !== 'idp') {
                continue;
            }

            $grouped[$source][] = $attribute;
        }
        return $grouped;
    }

    public function getAttributeAggregatedAttributes(): array
    {
        return $this->arp->getNonIdpAttributes();
    }

    /**
     * @return bool
     */
    public function hasMotivations(): bool
    {
        return $this->arp->hasMotivations();
    }

    public function getMotivation(
        Attribute $attribute,
    ) {
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
