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

class GlobalViewParameters
{
    /**
     * @var DataCollectorTranslator
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
     * @param DataCollectorTranslator $translator
     * @param array $helpUrls
     * @param array $platformUrls
     * @param array $termsOfServiceUrls
     */
    private function __construct(
        DataCollectorTranslator $translator,
        array $helpUrls,
        array $platformUrls,
        array $termsOfServiceUrls
    ) {

        $this->translator = $translator;
        $this->helpUrls = $helpUrls;
        $this->platformUrls = $platformUrls;
        $this->termsOfServiceUrls = $termsOfServiceUrls;
    }

    /**
     * @param DataCollectorTranslator $translator
     * @param array $locales
     * @param array $helpUrls
     * @param array $platformUrls
     * @param array $termsOfServiceUrls
     * @return GlobalViewParameters
     */
    public static function createForLocales(
        DataCollectorTranslator $translator,
        array $locales,
        array $helpUrls,
        array $platformUrls,
        array $termsOfServiceUrls
    ) {
        if (!in_array($translator->getLocale(), $locales)) {
            throw new InvalidArgumentException(sprintf(
                'Locale "%s" is not configured as a valid locale. Currently configured locales: %s',
                $translator->getLocale(),
                implode(', ', $locales)
            ));
        }

        foreach ($locales as $locale) {
            Assert::keyExists($helpUrls, $locale, sprintf('Locale "%s" not configured for helpUrls', $locale));
            Assert::keyExists($platformUrls, $locale, sprintf('Locale "%s" not configured for platformUrls', $locale));
            Assert::keyExists($termsOfServiceUrls, $locale, sprintf(
                'Locale "%s" not configured for termsOfServiceUrls',
                $locale
            ));
        }

        self::checkIfNoExtraLocales($helpUrls, $locales, 'helpUrls');
        self::checkIfNoExtraLocales($platformUrls, $locales, 'platformUrls');
        self::checkIfNoExtraLocales($termsOfServiceUrls, $locales, 'termsOfServiceUrls');

        return new self($translator, $helpUrls, $platformUrls, $termsOfServiceUrls);
    }

    /**
     * @param $translations
     * @param $allowedLocales
     * @param $name
     */
    private static function checkIfNoExtraLocales($translations, $allowedLocales, $name)
    {
        $localeDifference = array_diff(array_keys($translations), $allowedLocales);

        if (!empty($localeDifference)) {
            throw new InvalidArgumentException(sprintf(
                'Unconfigured locales defined for %s: %s',
                $name,
                implode(', ', $localeDifference)
            ));
        }
    }

    /**
     * @return array
     */
    public function getHelpUrl()
    {
        return $this->resolveLocale($this->helpUrls, $this->translator->getLocale());
    }

    /**
     * @return array
     */
    public function getPlatformUrl()
    {
        return $this->resolveLocale($this->platformUrls, $this->translator->getLocale());
    }

    /**
     * @return array
     */
    public function getTermsOfServiceUrl()
    {
        return $this->resolveLocale($this->termsOfServiceUrls, $this->translator->getLocale());
    }

    /**
     * @param array $configuration
     * @param $locale
     * @return string
     */
    private function resolveLocale(array $configuration, $locale)
    {
        if ($configuration[$locale]) {
            return $configuration[$locale];
        }

        return '';
    }
}
