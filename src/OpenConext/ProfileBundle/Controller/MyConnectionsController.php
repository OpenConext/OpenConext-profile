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

use OpenConext\Profile\Api\AuthenticatedUserProviderInterface;
use OpenConext\Profile\Value\EmailAddressSupport;
use OpenConext\ProfileBundle\Form\Type\ConfirmConnectionDeleteType;
use OpenConext\ProfileBundle\Service\AttributeAggregationService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class MyConnectionsController extends AbstractController
{
    public function __construct(
        private readonly Environment $templateEngine,
        private readonly LoggerInterface $logger,
        private readonly AttributeAggregationService $service,
        private readonly AuthenticatedUserProviderInterface $userProvider,
        private readonly EmailAddressSupport $mailTo,
        private readonly FormFactoryInterface $formFactory,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    #[Route(
        path: '/my-connections',
        name: 'profile.my_connections_overview',
        methods: ['GET', 'POST'],
        schemes: ['https'],
    )]
    public function overview(
        Request $request,
    ): Response {
        $this->logger->info('Showing My Connections page');

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
            '@OpenConextProfile/MyConnections/overview.html.twig',
            [
                'activeConnections' => $activeConnections,
                'availableConnections' => $availableConnections,
                'mailTo' => $this->mailTo->getEmailAddress(),
                'confirmForm' => $confirmationForm->createView(),
            ],
        ));
    }
}
