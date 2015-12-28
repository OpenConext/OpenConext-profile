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

final class ContactPerson
{
    /**
     * @var ContactType
     */
    private $contactType;

    /**
     * @var EmailAddress|null
     */
    private $emailAddress;

    public function __construct(ContactType $contactType, EmailAddress $emailAddress = null)
    {
        $this->contactType  = $contactType;
        $this->emailAddress = $emailAddress;
    }

    /**
     * @param ContactType $otherContactType
     * @return bool
     */
    public function hasContactTypeOf(ContactType $otherContactType)
    {
        return $this->contactType->equals($otherContactType);
    }

    /**
     * @return bool
     */
    public function hasEmailAddress()
    {
        return $this->emailAddress !== null;
    }

    /**
     * @return null|EmailAddress
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function __toString()
    {
        return $this->contactType . ': ' . $this->emailAddress;
    }
}
