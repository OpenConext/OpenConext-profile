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
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
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
     * @var DateTime|0
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
        $cookieSecure = true,
        $cookieHttpOnly = true
    ) {
        Assert::string($cookieDomain);
        Assert::string($cookieKey);
        Assert::boolean($cookieSecure);
        Assert::boolean($cookieHttpOnly);

        // If no date is specified for cookie expiration, a session cookie should be created
        if ($cookieExpirationDate === null) {
            $cookieExpirationDate = 0;
        }

        $this->cookieDomain         = $cookieDomain;
        $this->cookieKey            = $cookieKey;
        $this->cookieExpirationDate = $cookieExpirationDate;
        $this->cookieSecure         = $cookieSecure;
        $this->cookieHttpOnly       = $cookieHttpOnly;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        Assert::string($value);

        $this->cookieValue = $value;
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        return $this->cookieValue;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function loadValueFromCookie(GetResponseEvent $event)
    {
        $this->cookieValue = $event->getRequest()->cookies->get($this->cookieKey, null);
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function storeValueInCookie(FilterResponseEvent $event)
    {
        $event->getResponse()->headers->setCookie(new Cookie(
            $this->cookieKey,
            $this->cookieValue,
            $this->cookieExpirationDate,
            null,
            $this->cookieDomain,
            $this->cookieSecure,
            $this->cookieHttpOnly
        ));
    }

    public static function getSubscribedEvents()
    {
        return [
            // Must be loaded early
            KernelEvents::REQUEST  => [['loadValueFromCookie', 20]],
            KernelEvents::RESPONSE => [['storeValueInCookie']]
        ];
    }
}
