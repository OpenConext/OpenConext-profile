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

namespace OpenConext\ProfileBundle\Saml;

use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class StateHandler
{
    /**
     * @var NamespacedAttributeBag
     */
    private $attributeBag;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(NamespacedAttributeBag $attributeBag, SessionInterface $session)
    {
        $this->attributeBag = $attributeBag;
        $this->session = $session;
    }

    /**
     * @param string $originalRequestId
     * @return $this
     */
    public function setRequestId($originalRequestId)
    {
        $this->attributeBag->set('request_id', $originalRequestId);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRequestId()
    {
        return $this->attributeBag->get('request_id');
    }

    /**
     * @return bool
     */
    public function hasRequestId()
    {
        return $this->attributeBag->has('request_id');
    }

    /**
     * Removes the requestId from the session
     */
    public function clearRequestId()
    {
        $this->attributeBag->remove('request_id');
    }

    /**
     * @param string $uri
     */
    public function setCurrentRequestUri($uri)
    {
        $this->attributeBag->set('current_uri', $uri);
    }

    /**
     * @return string
     */
    public function getCurrentRequestUri()
    {
        $uri = $this->attributeBag->get('current_uri');
        $this->attributeBag->remove('current_uri');

        return $uri;
    }
}
