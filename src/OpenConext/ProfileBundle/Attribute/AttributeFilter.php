<?php

declare(strict_types = 1);

/**
 * Copyright 2017 SURFnet B.V.
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

use Surfnet\SamlBundle\SAML2\Attribute\AttributeSet;
use Surfnet\SamlBundle\SAML2\Attribute\Attribute;

final class AttributeFilter
{
    private static array $filterValues = [
        'commonName',
        'displayName',
        'mail',
        'uid',
        'eduPersonPrincipalName',
        'affiliation',
        'schacHomeOrganization',
    ];

    public function filter(
        AttributeSet $attributeSet,
    ): AttributeSet {
        /** @var Attribute[] $attributes */
        $attributes = $attributeSet->getIterator()->getArrayCopy();
        foreach ($attributes as $index => $attribute) {
            assert($attribute instanceof Attribute);
            $attributeName = $attribute->getAttributeDefinition()->getName();
            if (!in_array($attributeName, self::$filterValues)) {
                unset($attributes[$index]);
            }
        }
        return AttributeSet::create($attributes);
    }
}
