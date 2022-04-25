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
use OpenConext\Profile\Value\NameIdFormat;
use OpenConext\Profile\Value\ContactEmailAddress;
use OpenConext\Profile\Value\Entity;
use OpenConext\Profile\Value\Url;

class ServiceProvider
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
     * @var DisplayName
     */
    private $organizationDisplayName;

    /**
     * @var NameIdFormat
     */
    private $nameIdFormat;

    /**
     * @var Url|null
     */
    private $eulaUrl;

    /**
     * @var ContactEmailAddress|null
     */
    private $supportEmail;

    /**
     * @var Url|null
     */
    private $supportUrlEn;

    /**
     * @var Url|null
     */
    private $supportUrlNl;

    public function __construct(
        Entity $entity,
        DisplayName $displayName,
        DisplayName $organizationDisplayName,
        NameIdFormat $nameIdFormat,
        Url $eulaUrl = null,
        ContactEmailAddress $supportEmail = null,
        Url $supportUrlEn = null,
        Url $supportUrlNl = null
    ) {
        $this->entity       = $entity;
        $this->displayName  = $displayName;
        $this->organizationDisplayName = $organizationDisplayName;
        $this->nameIdFormat = $nameIdFormat;
        $this->eulaUrl      = $eulaUrl;
        $this->supportEmail = $supportEmail;
        $this->supportUrlEn = $supportUrlEn;
        $this->supportUrlNl = $supportUrlNl;
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
    public function getDisplayName(): DisplayName
    {
        return $this->displayName;
    }

    public function getOrganizationNameByLocale(string $locale): string
    {
        if (!$this->organizationDisplayName->hasFilledTranslationForLocale($locale)) {
            throw new LogicException(sprintf('Unable to retrieve organization display name for locale: %s', $locale));
        }
        return $this->organizationDisplayName->getTranslation($locale);
    }

    /**
     * @return NameIdFormat
     */
    public function getNameIdFormat()
    {
        return $this->nameIdFormat;
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
     * @return string
     */
    public function getSupportEmail()
    {
        if (!$this->hasSupportEmail()) {
            throw new LogicException('Service provider has no support e-mail address');
        }

        return $this->supportEmail->__toString();
    }

    /**
     * @param string $locale
     * @return null|Url
     */
    public function getSupportUrl($locale)
    {
        if ($locale === 'nl') {
            return $this->supportUrlNl;
        } else {
            return $this->supportUrlEn;
        }
    }

    /**
     * @param $locale
     * @return bool
     */
    public function hasSupportUrl($locale)
    {
        if ($locale === 'nl') {
            return $this->supportUrlNl !== null;
        } else {
            return $this->supportUrlEn !== null;
        }
    }
}
