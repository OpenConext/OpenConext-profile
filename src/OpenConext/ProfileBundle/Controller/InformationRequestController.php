<?php

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
use OpenConext\ProfileBundle\Security\Guard;
use OpenConext\ProfileBundle\Service\InformationRequestMailService;
use OpenConext\ProfileBundle\Service\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\EngineInterface;

class InformationRequestController
{
    /**
     * @var Guard
     */
    private $guard;

    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var InformationRequestMailService
     */
    private $informationRequestMailService;

    /**
     * @var AttributeFilter
     */
    private $attributeFilter;

    public function __construct(
        Guard $guard,
        EngineInterface $templateEngine,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        UserService $userService,
        AttributeFilter $attributeFilter,
        InformationRequestMailService $informationRequestMailService
    ) {
        $this->guard = $guard;
        $this->templateEngine = $templateEngine;
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->userService = $userService;
        $this->attributeFilter = $attributeFilter;
        $this->informationRequestMailService = $informationRequestMailService;
    }

    public function overviewAction()
    {
        $this->guard->userIsLoggedIn();

        $informationRequestMailForm = $this->formFactory->create(
            InformationRequestMailType::class,
            null,
            ['action' => $this->urlGenerator->generate('profile.information_request_send_mail')]
        );

        $attributes = $this->attributeFilter->filter($this->userService->getUser()->getAttributes());

        return new Response(
            $this->templateEngine->render(
                'OpenConextProfileBundle:InformationRequest:overview.html.twig',
                [
                    'attributes' => $attributes,
                    'informationRequestMailForm' => $informationRequestMailForm->createView()
                ]
            )
        );
    }

    public function sendMailAction()
    {
        $this->guard->userIsLoggedIn();

        $this->informationRequestMailService->sendInformationRequestMail();

        return new RedirectResponse($this->urlGenerator->generate('profile.information_request_confirm_mail_sent'));
    }

    public function confirmMailSentAction()
    {
        $this->guard->userIsLoggedIn();

        return new Response(
            $this->templateEngine->render('OpenConextProfileBundle:InformationRequest:confirmation.html.twig')
        );
    }
}
