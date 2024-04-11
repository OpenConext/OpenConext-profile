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

namespace OpenConext\ProfileBundle\Controller;

use OpenConext\ProfileBundle\Form\Type\AttributeSupportMailType;
use OpenConext\ProfileBundle\Service\AttributeSupportMailService;
use OpenConext\ProfileBundle\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class AttributeSupportController extends AbstractController
{
    public function __construct(
        private readonly Environment                 $templateEngine,
        private readonly FormFactoryInterface        $formFactory,
        private readonly UrlGeneratorInterface       $urlGenerator,
        private readonly UserService                 $userService,
        private readonly AttributeSupportMailService $attributeSupportMailService,
    ) {
    }

    public function overview(): Response
    {
        $attributeSupportMailForm = $this->formFactory->create(
            AttributeSupportMailType::class,
            null,
            ['action' => $this->urlGenerator->generate('profile.attribute_support_send_mail')],
        );

        return new Response(
            $this->templateEngine->render(
                '@OpenConextProfile/AttributeSupport/overview.html.twig',
                [
                    'attributes'               => $this->userService->getUser()->getAttributes(),
                    'attributeSupportMailForm' => $attributeSupportMailForm->createView()
                ],
            ),
        );
    }

    public function sendMail(): RedirectResponse
    {
        $this->attributeSupportMailService->sendAttributeSupportMail();

        return new RedirectResponse($this->urlGenerator->generate('profile.attribute_support_confirm_mail_sent'));
    }

    public function confirmMailSent(): Response
    {
        return new Response(
            $this->templateEngine->render('@OpenConextProfile/AttributeSupport/confirmation.html.twig'),
        );
    }
}
