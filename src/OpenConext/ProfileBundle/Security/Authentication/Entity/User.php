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

namespace OpenConext\ProfileBundle\Security\Authentication\Entity;

class User
{
    /**
     * @var string
     */
    public $nameId;

    /**
     * @var string
     */
    public $institution;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $commonName;

    /**
     * Using toString in order to comply with AbstractToken's setUser method.
     * Not implementing a UserInterface, because methods defined there will not be used.
     *
     * @return string
     */
    public function __toString()
    {
        return serialize([
            $this->nameId,
            $this->institution,
            $this->email,
            $this->commonName
        ]);
    }
}
