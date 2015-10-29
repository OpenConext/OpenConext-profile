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

use Symfony\Component\Form\FormFactoryInterface;
use Twig_Extension as Extension;
use Twig_SimpleFunction as SimpleFunction;

final class LocaleExtension extends Extension
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function getFunctions()
    {
        return [
            new SimpleFunction('profile_locale_switcher', [$this, 'getLocalePreferenceForm'])
        ];
    }

    public function getLocalePreferenceForm($currentLocale, array $routeParameters)
    {
        $form = $this->formFactory->create(
            'profile_switch_locale',
            null,
            ['route_parameters' => $routeParameters, 'current_locale' => $currentLocale]
        );

        return $form->createView();
    }

    public function getName()
    {
        return 'profile_locale';
    }
}
