<?php

declare(strict_types = 1);

/**
 * Copyright 2017 SURFnet B.V.
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

use OpenConext\ProfileBundle\Attribute\AttributeFilter;
use OpenConext\ProfileBundle\Form\Type\InformationRequestMailType;
use OpenConext\ProfileBundle\Service\InformationRequestMailService;
use OpenConext\ProfileBundle\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class InformationRequestController extends AbstractController
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UserService $userService,
        private readonly AttributeFilter $attributeFilter,
        private readonly InformationRequestMailService $informationRequestMailService,
    ) {
    }

    #[Route(
        path: '/information-request',
        name: 'profile.information_request_overview',
        methods: ['GET'],
        schemes: ['https'],
    )]
    public function overview(): Response
    {
        $informationRequestMailForm = $this->formFactory->create(
            InformationRequestMailType::class,
            null,
            ['action' => $this->urlGenerator->generate('profile.information_request_send_mail')],
        );

        $attributes = $this->attributeFilter->filter($this->userService->getUser()->getAttributes());

        return $this->render(
            '@OpenConextProfile/InformationRequest/overview.html.twig',
            [
                    'attributes' => $attributes,
                    'informationRequestMailForm' => $informationRequestMailForm->createView()
                ],
        );
    }

    #[Route(
        path: '/information-request/send-mail',
        name: 'profile.information_request_send_mail',
        methods: ['POST'],
        schemes: ['https'],
    )]
    public function sendMail(): RedirectResponse
    {
        $this->informationRequestMailService->sendInformationRequestMail();

        return new RedirectResponse($this->urlGenerator->generate('profile.information_request_confirm_mail_sent'));
    }

    #[Route(
        path: '/information-request/confirmation',
        name: 'profile.information_request_confirm_mail_sent',
        methods: ['GET'],
        schemes: ['https'],
    )]
    public function confirmMailSent(): Response
    {
        return $this->render('@OpenConextProfile/InformationRequest/confirmation.html.twig');
    }
}
