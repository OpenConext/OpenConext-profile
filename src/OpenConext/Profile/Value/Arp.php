<?php

declare(strict_types = 1);

/**
 * Copyright 2018 SURFnet B.V.
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

use OpenConext\Profile\Exception\InvalidArpDataException;
use Surfnet\SamlBundle\Exception\UnknownUrnException;
use Surfnet\SamlBundle\SAML2\Attribute\Attribute;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDefinition;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDictionary;

/**
 * The Arp value object represents the Arp configuration for a given entity.
 */
final class Arp
{
    /**
     * @var array The arp configuration is grouped on source. The source values are a collection of Attribute
     */
    private readonly array $arp;

    public static function createWith(
        array $arp,
        AttributeDictionary $dictionary = null,
    ): self {

        $arp = self::sanitizeARPData($arp);

        // Input validation
        foreach ($arp as $attributeInformation) {

            if (!self::isValidAttribute($attributeInformation)) {
                throw new InvalidArpDataException('The attribute information is formatted invalidly.');
            }
        }

        return new self($arp, $dictionary);
    }

    private function __construct(
        array $arp,
        AttributeDictionary $dictionary = null,
    ) {
        $arpCollection = [];

        // Create Attribute instances for all attributes
        foreach ($arp as $attributeName => $attributeDefinitionInformation) {
            $attributeSource = 'idp';
            $attributeDefinition = new AttributeDefinition($attributeName, $attributeName, $attributeName);

            // When the dictionary is available. Lookup the attribute in dictionary to load friendly attribute names
            if (!is_null($dictionary)) {
                try {
                    $attributeDefinition = $dictionary->getAttributeDefinitionByUrn($attributeName);
                } catch (UnknownUrnException) {
                    // Use the previously created attributeDefinition.
                }
            }

            if (isset($attributeDefinitionInformation[0]['source'])) {
                $attributeSource = $attributeDefinitionInformation[0]['source'];
            }

            // The arp is grouped on attribute source
            $arpCollection[$attributeSource][] = new Attribute($attributeDefinition, $attributeDefinitionInformation);
        }

        $this->arp = $arpCollection;
    }

    /**
     * Tests the structure of the Arp attribute information.
     *
     * This information should be an array, should have the attribute names as its keys and have array values.
     * These array values can have three keys (value, source and motivation) the values of these entries should
     * be of type string.
     *
     * Example of a valid attribute information array:
     *
     * [
     *   'urn.mace.email' => [
     *     ['value' => '*'],
     *   ],
     *   'urn.mace.eduPersonTargetedId' => [
     *     [
     *       'value' => '*',
     *       'source' => 'sab',
     *     ]
     *   ],
     *   'urn.mace.orcid' => [
     *     [
     *       'value' => 'orcid.org/\d{4}-\d{4}',
     *       'source' => 'orcid',
     *     ],
     *     [
     *       'value' => 'sandbox.orcid.org/\d{4}-\d{4}',
     *       'source' => 'orcid',
     *     ],
     *   ],
     *   'urn.mace.eduPersonAffiliation' => [
     *     [
     *       'value' => '*',
     *       'source' => 'sab',
     *       'motivation' => 'A motivation provided by the SP.'
     *     ]
     *   ],
     * ]
     *
     * @return bool
     */
    private static function isValidAttribute(
        array $attributeInformation,
    ): bool {
        foreach ($attributeInformation as $attributeInformationEntry) {
            if (!isset($attributeInformationEntry['value'])) {
                return false;
            }

            foreach ($attributeInformationEntry as $value) {
                if (!is_string($value)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @return array
     */
    public function getNonIdpAttributes()
    {
        $attributes = $this->getAttributesGroupedBySource();
        unset($attributes['idp']);
        return $attributes;
    }

    public function getAttributesGroupedBySource()
    {
        return $this->arp;
    }

    public function hasMotivations(): bool
    {
        foreach ($this->arp as $arpSource) {
            foreach ($arpSource as $arpEntry) {
                $values = $arpEntry->getValue();
                $attributeValue = reset($values);
                if (isset($attributeValue['motivation'])) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getMotivationFor(
        Attribute $attribute,
    ) {
        foreach ($this->arp as $arpSource) {
            foreach ($arpSource as $arpEntry) {
                if ($attribute->getAttributeDefinition()->getUrnMace() == $arpEntry->getAttributeDefinition()->getUrnMace()) {
                    $values = $arpEntry->getValue();
                    $attributeValue = reset($values);
                    if (isset($attributeValue['motivation'])) {
                        return $attributeValue['motivation'];
                    }
                }
            }
        }
        return '';
    }

    /**
     * Accordingly to https://www.pivotaltracker.com/n/projects/1453004/stories/187607790
     * the ARP data should only contain the following keys: value, source, motivation.
     * This function will filter out all other keys, which are to be ignored.
     */
    private static function sanitizeARPData(array $arp): array
    {
        $validKeys = ['value', 'source', 'motivation'];

        $sanitizedArp = [];
        foreach ($arp as $attributeName => $attributeInformation) {
            if (!is_array($attributeInformation)) {
                throw new InvalidArpDataException('The attribute information in the arp should be an array.');
            }

            $sanitizedAttributeInformation = [];
            foreach ($attributeInformation as $attributeInformationEntry) {
                if (!is_array($attributeInformationEntry)) {
                    continue;
                }

                $sanitizedAttributeInformation[] = array_filter(
                    $attributeInformationEntry,
                    function ($key) use ($validKeys) {
                        return in_array($key, $validKeys);
                    },
                    ARRAY_FILTER_USE_KEY
                );
            }

            $sanitizedArp[$attributeName] = $sanitizedAttributeInformation;
        }

        return $sanitizedArp;
    }
}
