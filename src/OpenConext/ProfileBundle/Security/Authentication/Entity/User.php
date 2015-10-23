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

use Serializable;

class User implements Serializable
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

    public function serialize()
    {
        return serialize([
            $this->nameId,
            $this->institution,
            $this->email,
            $this->commonName
        ]);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        list(
            $this->nameId,
            $this->institution,
            $this->email,
            $this->commonName
        ) = $data;
    }
}
