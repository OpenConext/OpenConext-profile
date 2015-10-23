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

class StateHandler
{
    const SESSION_PATH = 'openconext/profile/request';

    /**
     * @var NamespacedAttributeBag
     */
    private $attributeBag;

    public function __construct(NamespacedAttributeBag $attributeBag)
    {
        $this->attributeBag = $attributeBag;
    }

    /**
     * @param string $originalRequestId
     * @return $this
     */
    public function setRequestId($originalRequestId)
    {
        $this->set('request_id', $originalRequestId);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRequestId()
    {
        return $this->get('request_id');
    }

    /**
     * @return bool
     */
    public function hasRequestId()
    {
        return $this->attributeBag->has(self::SESSION_PATH . 'request_id');
    }

    /**
     * Removes the requestId from the session
     */
    public function clearRequestId()
    {
        $this->remove('request_id');
    }

    /**
     * @param string $uri
     */
    public function setCurrentRequestUri($uri)
    {
        $this->set('current_uri', $uri);
    }

    /**
     * @return string
     */
    public function getCurrentRequestUri()
    {
        $uri = $this->get('current_uri');
        $this->remove('current_uri');

        return $uri;
    }

    /**
     * Migrates the current session to a new session id while maintaining all
     * session attributes.
     */
    public function migrate()
    {
        $this->attributeBag->migrate();
    }

    /**
     * @param string $key
     * @param mixed $value Any scalar
     */
    protected function set($key, $value)
    {
        $this->attributeBag->set(self::SESSION_PATH . $key, $value);
    }

    /**
     * @param string $key
     * @return mixed|null Any scalar
     */
    protected function get($key)
    {
        return $this->attributeBag->get(self::SESSION_PATH . $key);
    }

    /**
     * @param $key
     */
    protected function remove($key)
    {
        $this->attributeBag->remove(self::SESSION_PATH . $key);
    }
}
