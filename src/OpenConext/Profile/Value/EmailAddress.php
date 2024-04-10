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

use OpenConext\Profile\Assert;
use Stringable;

final class EmailAddress implements EmailAddressSupport, EmailAddressInformationRequest, Stringable
{
    /**
     * @var string
     */
    private $emailAddress;

    /**
     * @param string $emailAddress
     */
    public function __construct($emailAddress)
    {
        Assert::string($emailAddress, 'E-mail address "%s" must be a string');
        Assert::email($emailAddress);

        $this->emailAddress = $emailAddress;
    }

    /**
     * @return bool
     */
    public function equals(EmailAddress $other): bool
    {
        return $this->emailAddress === $other->emailAddress;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function __toString(): string
    {
        return $this->emailAddress;
    }
}
