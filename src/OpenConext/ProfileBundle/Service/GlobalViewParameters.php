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

use InvalidArgumentException;
use OpenConext\Profile\Assert;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Translation\TranslatorInterface;

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
    private $platformUrls;

    /**
     * @var array
     */
    private $termsOfServiceUrls;

    /**
     * @param TranslatorInterface $translator
     * @param array $locales
     * @param array $helpUrls
     * @param array $platformUrls
     * @param array $termsOfServiceUrls
     */
    public function __construct(
        TranslatorInterface $translator,
        array $locales,
        array $helpUrls,
        array $platformUrls,
        array $termsOfServiceUrls
    ) {
        Assert::keysAre($helpUrls, $locales);
        Assert::keysAre($platformUrls, $locales);
        Assert::keysAre($termsOfServiceUrls, $locales);

        $this->translator = $translator;
        $this->helpUrls = $helpUrls;
        $this->platformUrls = $platformUrls;
        $this->termsOfServiceUrls = $termsOfServiceUrls;
    }

    /**
     * @return array
     */
    public function getHelpUrl()
    {
        return $this->helpUrls[$this->translator->getLocale()];
    }

    /**
     * @return array
     */
    public function getPlatformUrl()
    {
        return $this->platformUrls[$this->translator->getLocale()];
    }

    /**
     * @return array
     */
    public function getTermsOfServiceUrl()
    {
        return $this->termsOfServiceUrls[$this->translator->getLocale()];
    }
}
