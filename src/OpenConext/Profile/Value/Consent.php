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

use DateTimeImmutable;
use OpenConext\Profile\Value\Consent\ServiceProvider;

final class Consent
{
    /**
     * @var ServiceProvider
     */
    private $serviceProvider;

    /**
     * @var DateTimeImmutable
     */
    private $consentGivenOn;

    /**
     * @var ConsentType
     */
    private $consentType;

    /**
     * @param ServiceProvider $serviceProvider
     * @param DateTimeImmutable $consentGivenOn
     * @param ConsentType $consentType
     */
    public function __construct(
        ServiceProvider $serviceProvider,
        DateTimeImmutable $consentGivenOn,
        ConsentType $consentType
    ) {
        $this->serviceProvider = $serviceProvider;
        $this->consentGivenOn  = $consentGivenOn;
        $this->consentType     = $consentType;
    }

    /**
     * @return ServiceProvider
     */
    public function getServiceProvider()
    {
        return $this->serviceProvider;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getConsentGivenOn()
    {
        return $this->consentGivenOn;
    }

    /**
     * @return ConsentType
     */
    public function getConsentType()
    {
        return $this->consentType;
    }
}
