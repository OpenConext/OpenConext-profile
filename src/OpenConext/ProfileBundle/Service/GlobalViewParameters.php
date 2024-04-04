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

namespace OpenConext\ProfileBundle\Service;

use OpenConext\Profile\Assert;
use Symfony\Contracts\Translation\TranslatorInterface ;

final class GlobalViewParameters
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var array
     */
    private $helpUrls;

    /**
     * @var array
     */
    private $privacyUrls;

    /**
     * @var array
     */
    private $platformUrls;

    /**
     * @var array
     */
    private $termsOfServiceUrls;

    /**
     * @var array
     */
    private $profileExplanationImageUrls;

    /**
     * @var array
     */
    private $attributeInformationUrls;

    /** @var bool */
    private $removeConsentEnabled;

    public function __construct(
        TranslatorInterface $translator,
        array $locales,
        array $helpUrls,
        array $privacyUrls,
        array $platformUrls,
        array $termsOfServiceUrls,
        array $profileExplanationImageUrls,
        array $attributeInformationUrls,
        bool $removeConsentEnabled,
    ) {
        Assert::keysArePresent($helpUrls, $locales);
        Assert::keysArePresent($privacyUrls, $locales);
        Assert::keysArePresent($platformUrls, $locales);
        Assert::keysArePresent($termsOfServiceUrls, $locales);
        Assert::keysArePresent($profileExplanationImageUrls, $locales);
        Assert::keysArePresent($attributeInformationUrls, $locales);

        $this->translator                  = $translator;
        $this->helpUrls                    = $helpUrls;
        $this->privacyUrls                 = $privacyUrls;
        $this->platformUrls                = $platformUrls;
        $this->termsOfServiceUrls          = $termsOfServiceUrls;
        $this->profileExplanationImageUrls = $profileExplanationImageUrls;
        $this->attributeInformationUrls    = $attributeInformationUrls;
        $this->removeConsentEnabled = $removeConsentEnabled;
    }

    /**
     * @return string
     */
    public function getHelpUrl()
    {
        return $this->helpUrls[$this->translator->getLocale()];
    }

    /**
     * @return string
     */
    public function getPrivacyUrl()
    {
        return $this->privacyUrls[$this->translator->getLocale()];
    }

    /**
     * @return string
     */
    public function getPlatformUrl()
    {
        return $this->platformUrls[$this->translator->getLocale()];
    }

    /**
     * @return string
     */
    public function getTermsOfServiceUrl()
    {
        return $this->termsOfServiceUrls[$this->translator->getLocale()];
    }

    /**
     * @return string
     */
    public function getProfileExplanationImage()
    {
        return $this->profileExplanationImageUrls[$this->translator->getLocale()];
    }

    /**
     * @return string
     */
    public function getAttributeInformationUrl()
    {
        return $this->attributeInformationUrls[$this->translator->getLocale()];
    }

    public function isRemoveConsentFeatureEnabled()
    {
        return $this->removeConsentEnabled;
    }
}
