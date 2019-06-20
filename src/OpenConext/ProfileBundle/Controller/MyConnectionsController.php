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

use OpenConext\Profile\Api\AuthenticatedUserProviderInterface;
use OpenConext\Profile\Value\EmailAddress;
use OpenConext\Profile\Value\EmailAddressSupport;
use OpenConext\ProfileBundle\Form\Type\ConfirmConnectionDeleteType;
use OpenConext\ProfileBundle\Security\Guard;
use OpenConext\ProfileBundle\Service\AttributeAggregationService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\EngineInterface;

class MyConnectionsController
{
    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * @var Guard
     */
    private $guard;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var AttributeAggregationService
     */
    private $service;

    /**
     * @var AuthenticatedUserProviderInterface
     */
    private $userProvider;

    /**
     * @var EmailAddress
     */
    private $mailTo;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @param EngineInterface $templateEngine
     * @param Guard $guard
     * @param LoggerInterface $logger
     * @param AttributeAggregationService $service
     * @param AuthenticatedUserProviderInterface $userProvider
     * @param EmailAddressSupport $mailTo
     * @param FormFactoryInterface $formFactory
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        EngineInterface $templateEngine,
        Guard $guard,
        LoggerInterface $logger,
        AttributeAggregationService $service,
        AuthenticatedUserProviderInterface $userProvider,
        EmailAddressSupport $mailTo,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->templateEngine = $templateEngine;
        $this->guard = $guard;
        $this->logger = $logger;
        $this->service = $service;
        $this->userProvider = $userProvider;
        $this->mailTo = $mailTo;
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function overviewAction(Request $request)
    {
        $this->guard->userIsLoggedIn();
        $this->logger->notice('Showing My Connections page');

        $user = $this->userProvider->getCurrentUser();
        $attributes = $this->service->findByUser($user);

        $activeConnections = [];
        $availableConnections = [];

        if ($attributes) {
            $activeConnections = $attributes->getActiveAttributes();
            $availableConnections = $attributes->getAvailableAttributes();
        }

        // For now only the ORCID connection form is created and added to the form.
        $confirmationForm = $this->formFactory->create(ConfirmConnectionDeleteType::class);

        $confirmationForm->handleRequest($request);
        if ($confirmationForm->isSubmitted() && $confirmationForm->isValid()) {
            $this->logger->notice('The authenticated user is disconnecting ORCID iD.');
            $this->service->disconnectAttributeFor($user, $attributes->getAttribute('ORCID'));
            return new RedirectResponse($this->urlGenerator->generate('profile.my_connections_overview'));
        }

        return new Response($this->templateEngine->render(
            'OpenConextProfileBundle:MyConnections:overview.html.twig',
            [
                'activeConnections' => $activeConnections,
                'availableConnections' => $availableConnections,
                'mailTo' => $this->mailTo->getEmailAddress(),
                'confirmForm' => $confirmationForm->createView(),
            ]
        ));
    }
}
