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

use DateTime;
use DateTimeImmutable;
use OpenConext\Profile\Assert;
use OpenConext\Profile\Exception\InvalidArgumentException;
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
     * @var DateTimeImmutable
     */
    private $lastUsedOn;

    /**
     * @param ServiceProvider $serviceProvider
     * @param DateTimeImmutable $consentGivenOn
     * @param DateTimeImmutable $lastUsedOn
     */
    public function __construct(
        ServiceProvider $serviceProvider,
        DateTimeImmutable $consentGivenOn,
        DateTimeImmutable $lastUsedOn
    ) {
        $this->serviceProvider = $serviceProvider;
        $this->consentGivenOn  = $consentGivenOn;
        $this->lastUsedOn      = $lastUsedOn;
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
     * @return DateTimeImmutable
     */
    public function getLastUsedOn()
    {
        return $this->lastUsedOn;
    }
}
