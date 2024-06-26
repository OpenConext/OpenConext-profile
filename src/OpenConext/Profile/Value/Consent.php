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

use DateTimeImmutable;
use OpenConext\Profile\Value\Consent\ServiceProvider;
use OpenConext\Profile\Value\ConsentType;

final readonly class Consent
{
    public function __construct(
        private ServiceProvider $serviceProvider,
        private DateTimeImmutable $consentGivenOn,
        private ConsentType $consentType,
    ) {
    }

    public function getServiceProvider(): ServiceProvider
    {
        return $this->serviceProvider;
    }

    public function getConsentGivenOn(): DateTimeImmutable
    {
        return $this->consentGivenOn;
    }

    public function getConsentType(): ConsentType
    {
        return $this->consentType;
    }
}
