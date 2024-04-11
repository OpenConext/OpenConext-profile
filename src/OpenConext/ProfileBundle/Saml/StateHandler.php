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

use Symfony\Component\HttpFoundation\RequestStack;

class StateHandler
{
    const REQUEST_ID = 'request_id';
    const CURRENT_URI = 'current_uri';
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function setRequestId(string $originalRequestId): StateHandler
    {
        $this->requestStack->getSession()->set(self::REQUEST_ID, $originalRequestId);

        return $this;
    }

    public function getRequestId(): ?string
    {
        return $this->requestStack->getSession()->get(self::REQUEST_ID);
    }

    public function hasRequestId(): bool
    {
        return $this->requestStack->getSession()->has(self::REQUEST_ID);
    }

    /**
     * Removes the requestId from the session
     */
    public function clearRequestId(): void
    {
        $this->requestStack->getSession()->remove(self::REQUEST_ID);
    }

    /**
     * @param string $uri
     */
    public function setCurrentRequestUri(string $uri): void
    {
        $this->requestStack->getSession()->set(self::CURRENT_URI, $uri);
    }

    public function getCurrentRequestUri(): string
    {
        $uri = $this->requestStack->getSession()->get(self::CURRENT_URI);
        $this->requestStack->getSession()->remove(self::CURRENT_URI);

        return $uri;
    }
}
