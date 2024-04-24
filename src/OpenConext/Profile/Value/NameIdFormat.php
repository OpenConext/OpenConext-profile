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

final class NameIdFormat
{
    public const PERSISTENT_IDENTIFIER = 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent';
    public const TRANSIENT_IDENTIFIER  = 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient';


    public function __construct(
        private ?string $value,
    ) {
        // A service provider usually always has a NameIDFormat, because it's
        // required by the manage web interface.
        //
        // In practice, programmatically created entities can be saved
        // without NameIDFormat. In those cases, we fall back to most likely
        // used value by EngineBlock: persistent.
        if ($value === null) {
            $value = self::PERSISTENT_IDENTIFIER;
        }

        $this->value = $value;
    }

    public function isPersistent(): bool
    {
        return $this->value === self::PERSISTENT_IDENTIFIER;
    }

    public function isTransient(): bool
    {
        return $this->value === self::TRANSIENT_IDENTIFIER;
    }
}
