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

namespace OpenConext\ProfileBundle\Storage;

use DateTime;
use OpenConext\Profile\Assert;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SingleCookieStorage implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $cookieDomain;

    /**
     * @var string
     */
    private $cookieKey;

    /**
     * @var string|null
     */
    private $cookieValue;

    /**
     * @var DateTime|null
     */
    private $cookieExpirationDate;

    /**
     * @var boolean
     */
    private $cookieSecure;

    /**
     * @var boolean
     */
    private $cookieHttpOnly;

    public function __construct(
        $cookieDomain,
        $cookieKey,
        DateTime $cookieExpirationDate = null,
        $cookieSecure = false,
        $cookieHttpOnly = true
    ) {
        Assert::string($cookieDomain, 'Cookie domain "%s" expected to be string, type %s given.');
        Assert::string($cookieKey, 'Cookie key "%s" expected to be string, type %s given.');
        Assert::boolean($cookieSecure, 'Cookie secure setting "%s" is expected to be boolean.');
        Assert::boolean($cookieHttpOnly, 'Cookie HttpOnly setting "%s" expected to be boolean');

        $this->cookieDomain         = $cookieDomain;
        $this->cookieKey            = $cookieKey;
        $this->cookieExpirationDate = $cookieExpirationDate;
        $this->cookieSecure         = $cookieSecure;
        $this->cookieHttpOnly       = $cookieHttpOnly;
    }

    public function setValue(string $value)
    {
        $this->cookieValue = $value;
    }

    public function getValue(): ?string
    {
        return $this->cookieValue;
    }

    public function loadValueFromCookie(RequestEvent $event)
    {
        $this->cookieValue = $event->getRequest()->cookies->get($this->cookieKey, null);
    }

    public function storeValueInCookie(ResponseEvent $event)
    {
        // If no date is specified for cookie expiration, a session cookie should be created
        $cookieExpirationDate = $this->cookieExpirationDate ?: 0;
        $event->getResponse()->headers->setCookie(
            Cookie::create(
                $this->cookieKey,
                $this->cookieValue,
                $cookieExpirationDate,
                null,
                $this->cookieDomain,
                $this->cookieSecure,
                $this->cookieHttpOnly
            )
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // Must be loaded early
            KernelEvents::REQUEST  => [['loadValueFromCookie', 20]],
            KernelEvents::RESPONSE => [['storeValueInCookie']]
        ];
    }
}
