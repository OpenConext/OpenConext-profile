<?php

declare(strict_types = 1);

/**
 * Copyright 2019 SURFnet B.V.
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

namespace OpenConext\Profile\Api;

use OpenConext\Profile\Value\ContactEmailAddress;
use OpenConext\Profile\Value\Locale;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeSet;

interface ApiUserInterface
{
    public function switchLocaleTo(
        Locale $locale,
    ): void;

    public function hasSupportContactEmail(): bool;

    public function getSupportContactEmail(): ?ContactEmailAddress;

    public function getLocale(): Locale;

    public function getAttributes(): AttributeSet;

    public function getId(): string;
}
