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

namespace OpenConext\ProfileBundle\Consent;

use Surfnet\SamlBundle\SAML2\Attribute\Attribute;
use Surfnet\SamlBundle\SAML2\Attribute\Filter\AttributeFilter as AttributeFilterInterface;

final class BlacklistFilter implements AttributeFilterInterface
{
    /**
     * @var string[]
     */
    const BLACKLIST = [
        'urn:oid:2.5.4.42',
        'urn:oid:2.5.4.3',
        'urn:oid:2.5.4.4',
        'urn:oid:2.16.840.1.113730.3.1.241',
        'urn:oid:0.9.2342.19200300.100.1.1',
        'urn:oid:0.9.2342.19200300.100.1.3',
        'urn:oid:1.3.6.1.4.1.1466.115.121.1.15',
        'urn:oid:1.3.6.1.4.1.5923.1.1.1.6',
        'coin:',
        'urn:nl.surfconext.licenseInfo',
        'urn:mace:dir:attribute-def:isMemberOf',
        'urn:oid:1.3.6.1.4.1.1076.20.40.40.1',
        'urn:oid:1.3.6.1.4.1.5923.1.1.1.10',
    ];

    /**
     * @param Attribute $attribute
     * @return bool
     */
    public function allows(Attribute $attribute)
    {
        $urnMace = $attribute->getAttributeDefinition()->getUrnMace();
        $urnOid  = $attribute->getAttributeDefinition()->getUrnOid();

        foreach (self::BLACKLIST as $blacklistedAttribute) {
            if (strpos($urnMace, $blacklistedAttribute) !== false || strpos($urnOid, $blacklistedAttribute) !== false) {
                return false;
            }
        }

        return true;
    }
}
