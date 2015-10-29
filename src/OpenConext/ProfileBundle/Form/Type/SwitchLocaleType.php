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

namespace OpenConext\ProfileBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SwitchLocaleType extends AbstractType
{
    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction($this->urlGenerator->generate('profile.locale_switch_locale', ['return-url' => $options['return_url']]));
        $builder->setMethod('POST');

        $builder->add('locale_en', 'submit', [
            'label' => 'EN',
            'attr' => [
                'class' => ($options['current_locale'] === 'en') ? 'active' : ''
            ]
        ]);
        $builder->add('locale_nl', 'submit', [
            'label' => 'NL',
            'attr' => [
                'class' => ($options['current_locale'] === 'nl') ? 'active' : '',
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'return_url' => '',
            'current_locale' => null,
        ]);

        $resolver->isRequired('return_url');

        $resolver->setAllowedTypes('current_locale', 'string');
        $resolver->setAllowedTypes('return_url', 'string');
    }

    public function getName()
    {
        return 'profile_switch_locale';
    }
}
