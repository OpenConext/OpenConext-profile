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

namespace OpenConext\ProfileBundle\Twig;

use OpenConext\ProfileBundle\Form\Type\SwitchLocaleType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class LocaleExtension extends AbstractExtension
{
    private string $locale = 'en';

    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        RequestStack $requestStack,
        string $defaultLocale,
    ) {
        $this->locale = $this->retrieveLocale($requestStack, $defaultLocale);
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('profile_locale_switcher', $this->getLocalePreferenceForm(...)),
            new TwigFunction('locale', $this->getLocale(...)),
        ];
    }

    public function getLocalePreferenceForm(string $returnUrl): FormView
    {
        $form = $this->formFactory->create(
            SwitchLocaleType::class,
            null,
            ['return_url' => $returnUrl],
        );

        return $form->createView();
    }

    public function getName(): string
    {
        return 'profile_locale';
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    private function retrieveLocale(RequestStack $requestStack, $defaultLocale): string
    {
        $currentRequest = $requestStack->getCurrentRequest();
        $locale = $defaultLocale;
        if ($currentRequest) {
            $locale = $currentRequest->getLocale();
        }
        return $locale;
    }
}
