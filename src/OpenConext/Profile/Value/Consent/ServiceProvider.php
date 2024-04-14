<?php

declare(strict_types = 1);

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
use OpenConext\Profile\Value\ContactEmailAddress;
use OpenConext\Profile\Value\DisplayName;
use OpenConext\Profile\Value\Entity;
use OpenConext\Profile\Value\NameIdFormat;
use OpenConext\Profile\Value\Url;

class ServiceProvider
{
    public function __construct(
        private readonly Entity $entity,
        private readonly DisplayName $displayName,
        private readonly DisplayName $organizationDisplayName,
        private readonly NameIdFormat $nameIdFormat,
        private readonly ?Url $eulaUrl = null,
        private readonly ?ContactEmailAddress $supportEmail = null,
        private readonly ?Url $supportUrlEn = null,
        private readonly ?Url $supportUrlNl = null,
    ) {
    }

    public function getEntity(): Entity
    {
        return $this->entity;
    }

    public function getDisplayName(): DisplayName
    {
        return $this->displayName;
    }

    public function getOrganizationNameByLocale(
        string $locale,
    ): string {
        if (!$this->organizationDisplayName->hasFilledTranslationForLocale($locale)) {
            throw new LogicException(sprintf('Unable to retrieve organization display name for locale: %s', $locale));
        }
        return $this->organizationDisplayName->getTranslation($locale);
    }

    public function getNameIdFormat(): NameIdFormat
    {
        return $this->nameIdFormat;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getLocaleAwareEntityName(
        $locale,
    ) {
        Assert::string($locale);

        if ($this->displayName->hasFilledTranslationForLocale($locale)) {
            return $this->displayName->getTranslation($locale);
        }

        return $this->entity->getEntityId()->getEntityId();
    }

    public function hasEulaUrl(): bool
    {
        return $this->eulaUrl !== null;
    }

    public function getEulaUrl(): ?Url
    {
        if (!$this->hasEulaUrl()) {
            throw new LogicException('Service provider has no EULA url');
        }

        return $this->eulaUrl;
    }

    public function hasSupportEmail(): bool
    {
        return $this->supportEmail !== null;
    }

    public function getSupportEmail(): string
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
    public function getSupportUrl(
        $locale,
    ): ?Url {
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
    public function hasSupportUrl(
        $locale,
    ): bool {
        if ($locale === 'nl') {
            return $this->supportUrlNl !== null;
        } else {
            return $this->supportUrlEn !== null;
        }
    }
}
