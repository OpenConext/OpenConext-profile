<?php

/**
 * Copyright 2018 SURFnet B.V.
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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfirmConnectionDeleteType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'confirmed',
                CheckboxType::class,
                [
                    'attr' => ['class' => 'mdl-checkbox__input confirmation'],
                    'label' => 'profile.confirm_connection_delete.confirm_label',
                    'label_attr' => ['class' => 'confirmation-label'],
                    'empty_data' => false,
                    'required' => true,
                ]
            )
            ->add(
                'confirm-button',
                SubmitType::class,
                [
                    'attr' => [
                        'class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect ' .
                                   'mdl-button--accent confirm-button',
                        'disabled' => 'disabled',
                    ],
                    'label' => 'profile.confirm_connection_delete.confirm',
                ]
            );
    }

    public function getBlockPrefix()
    {
        return 'profile_confirm_connection_delete';
    }
}
