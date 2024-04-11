<?php

/**
 * Copyright 2010 SURFnet B.V.
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

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class I18n extends AbstractExtension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters(): array
    {
        return array(
            new TwigFilter('trans', array($this, 'translateSingular')),
            new TwigFilter('transchoice', array($this, 'translatePlural')),
        );
    }

    /**
     * @return string
     */
    public function translateSingular(): string
    {
        $args = func_get_args();
        return call_user_func_array(
            [$this->translator, 'trans'],
            $this->prepareDefaultPlaceholders($args),
        );
    }

    /**
     * @return string
     */
    public function translatePlural(): string
    {
        $args = func_get_args();
        return call_user_func_array(
            [$this->translator, 'transChoice'],
            $this->prepareDefaultPlaceholders($args),
        );
    }

    /**
     * @param array $args
     * @return array
     */
    private function prepareDefaultPlaceholders(array $args): array
    {
        $args[1]['%suiteName%'] = $this->translator->trans('general.suite_name');
        $args[1]['%organisationNoun%'] = $this->translator->trans('general.organisation_noun');

        return $args;
    }
}
