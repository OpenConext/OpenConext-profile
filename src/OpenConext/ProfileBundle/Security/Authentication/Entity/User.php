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

use Surfnet\SamlBundle\SAML2\Attribute\AttributeSet;

class User implements \Serializable
{
    /**
     * @var AttributeSet
     */
    private $attributes;

    /**
     * @param AttributeSet $attributes
     */
    public function __construct(AttributeSet $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return AttributeSet
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function serialize()
    {
        return serialize($this->attributes);
    }

    public function unserialize($serialized)
    {
        $this->attributes = unserialize($serialized);
    }

    /**
     * Using toString in order to comply with AbstractToken's setUser method,
     * which uses the string representation to detect changes in the user object.
     * Not implementing a UserInterface, because methods defined there will not be used.
     *
     * @return string
     */
    public function __toString()
    {
        return serialize($this);
    }
}
