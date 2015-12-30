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

namespace OpenConext\ProfileBundle\Controller;

use OpenConext\ProfileBundle\Security\Guard;
use OpenConext\ProfileBundle\Service\AttributeSupportMailService;
use OpenConext\ProfileBundle\Service\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\EngineInterface;

class AttributeSupportController
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
     * @var AttributeSupportMailService
     */
    private $attributeSupportMailService;

    public function __construct(
        Guard $guard,
        EngineInterface $templateEngine,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        UserService $userService,
        AttributeSupportMailService $attributeSupportMailService
    ) {
        $this->guard                       = $guard;
        $this->templateEngine              = $templateEngine;
        $this->formFactory                 = $formFactory;
        $this->urlGenerator                = $urlGenerator;
        $this->userService                 = $userService;
        $this->attributeSupportMailService = $attributeSupportMailService;
    }

    public function overviewAction()
    {
        $this->guard->userIsLoggedIn();

        $attributeSupportMailFormView = $this->formFactory->create('profile_attribute_support_mail')->createView();

        return new Response(
            $this->templateEngine->render(
                'OpenConextProfileBundle:AttributeSupport:overview.html.twig',
                [
                    'attributes'               => $this->userService->getUser()->getAttributes(),
                    'attributeSupportMailForm' => $attributeSupportMailFormView
                ]
            )
        );
    }

    public function sendMailAction()
    {
        $this->attributeSupportMailService->sendAttributeSupportMail();

        return new RedirectResponse($this->urlGenerator->generate('profile.attribute_support_confirm_mail_sent'));
    }

    public function confirmMailSentAction()
    {
        return new Response(
            $this->templateEngine->render('OpenConextProfileBundle:AttributeSupport:confirmation.html.twig')
        );
    }
}
