<?php

declare(strict_types = 1);

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

namespace OpenConext\ProfileBundle\Attribute;

use SAML2\Assertion;
use Surfnet\SamlBundle\Exception\UnknownUrnException;
use Surfnet\SamlBundle\SAML2\Attribute\Attribute;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDefinition;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDictionary;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSet;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSetFactory;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSetInterface;

final class AttributeSetWithFallbacks extends AttributeSet implements AttributeSetFactory, AttributeSetInterface
{
    public static function createFrom(
        Assertion $assertion,
        AttributeDictionary $attributeDictionary,
    ): AttributeSet {
        $attributeSet = new AttributeSetWithFallbacks();

        foreach ($assertion->getAttributes() as $urn => $attributeValue) {
            try {
                $attributeDefinition = $attributeDictionary->getAttributeDefinitionByUrn($urn);
            } catch (UnknownUrnException) {
                $attributeDefinition = new AttributeDefinition($urn, $urn, $urn);
            }

            $attributeSet->initializeWith(new Attribute($attributeDefinition, $attributeValue));
        }

        return $attributeSet;
    }

    public static function create(array $attributes): AttributeSet
    {
        $attributeSet = new AttributeSetWithFallbacks();

        foreach ($attributes as $attribute) {
            $attributeSet->initializeWith($attribute);
        }

        return $attributeSet;
    }

    private function __construct()
    {
    }
}
