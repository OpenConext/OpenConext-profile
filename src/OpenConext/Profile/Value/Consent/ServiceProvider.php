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

namespace OpenConext\Profile\Value\Consent;

use OpenConext\EngineBlockApiClientBundle\Exception\LogicException;
use OpenConext\Profile\Assert;
use OpenConext\Profile\Value\DisplayName;
use OpenConext\Profile\Value\EmailAddress;
use OpenConext\Profile\Value\Entity;
use OpenConext\Profile\Value\Url;

final class ServiceProvider
{
    /**
     * @var Entity
     */
    private $entity;

    /**
     * @var DisplayName
     */
    private $displayName;

    /**
     * @var Url|null
     */
    private $eulaUrl;

    /**
     * @var EmailAddress|null
     */
    private $supportEmail;

    public function __construct(
        Entity $entity,
        DisplayName $displayName,
        Url $eulaUrl = null,
        EmailAddress $supportEmail = null
    ) {
        $this->entity       = $entity;
        $this->displayName  = $displayName;
        $this->eulaUrl      = $eulaUrl;
        $this->supportEmail = $supportEmail;
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return DisplayName
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getLocaleAwareEntityName($locale)
    {
        Assert::string($locale);

        if ($this->displayName->hasFilledTranslationForLocale($locale)) {
            return $this->displayName->getTranslation($locale);
        }

        return $this->entity->getEntityId()->getEntityId();
    }

    /**
     * @return bool
     */
    public function hasEulaUrl()
    {
        return $this->eulaUrl !== null;
    }

    /**
     * @return Url
     */
    public function getEulaUrl()
    {
        if (!$this->hasEulaUrl()) {
            throw new LogicException('Service provider has no EULA url');
        }

        return $this->eulaUrl;
    }

    /**
     * @return bool
     */
    public function hasSupportEmail()
    {
        return $this->supportEmail !== null;
    }

    /**
     * @return EmailAddress
     */
    public function getSupportEmail()
    {
        if (!$this->hasSupportEmail()) {
            throw new LogicException('Service provider has no support e-mail address');
        }

        return $this->supportEmail;
    }
}
