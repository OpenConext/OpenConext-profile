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

use OpenConext\EngineBlockApiClientBundle\Repository\ConsentRepository;
use OpenConext\EngineBlockApiClientBundle\Repository\InstitutionRepository;
use OpenConext\Profile\Api\AuthenticatedUserProviderInterface;
use OpenConext\Profile\Entity\AuthenticatedUser;
use OpenConext\Profile\Value\SpecifiedConsent;
use OpenConext\Profile\Value\SpecifiedConsentList;
use OpenConext\ProfileBundle\Service\SpecifiedConsentListService;
use OpenConext\ProfileBundle\Security\Guard;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class MyServicesController
{
    /**
     * @var Environment
     */
    private $templateEngine;

    /**
     * @var SpecifiedConsentListService
     */
    private $specifiedConsentListService;

    /**
     * @var AuthenticatedUserProviderInterface
     */
    private $authenticatedUserProvider;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var Guard
     */
    private $guard;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var InstitutionRepository
     */
    private $institutionRepository;

    /** @var bool */
    private $removeConsentEnabled;

    public function __construct(
        Environment $templateEngine,
        AuthenticatedUserProviderInterface $authenticatedUserProvider,
        SpecifiedConsentListService $specifiedConsentListService,
        Guard $guard,
        UrlGeneratorInterface $urlGenerator,
        LoggerInterface $logger,
        InstitutionRepository $institutionRepository,
        bool $removeConsentEnabled
    ) {
        $this->templateEngine = $templateEngine;
        $this->authenticatedUserProvider = $authenticatedUserProvider;
        $this->specifiedConsentListService = $specifiedConsentListService;
        $this->guard = $guard;
        $this->urlGenerator = $urlGenerator;
        $this->logger = $logger;
        $this->institutionRepository = $institutionRepository;
        $this->removeConsentEnabled = $removeConsentEnabled;
    }

    public function overviewAction(Request $request)
    {
        $this->guard->userIsLoggedIn();

        $this->logger->notice('User requested My Services page');

        $locale = $request->getLocale();
        $user = $this->authenticatedUserProvider->getCurrentUser();
        $specifiedConsentList = $this->specifiedConsentListService->getListFor($user);
        $specifiedConsentList->sortByDisplayName($locale);

        $this->logger->notice(sprintf('Showing %s services on My Services page', count($specifiedConsentList)));

        list(
            'displayName' => $displayName,
            'logo' => $logo
        ) = $this->institutionRepository->getDisplayNameAndLogoForIdp($user, $locale);

        return new Response($this->templateEngine->render(
            '@OpenConextProfile/MyServices/overview.html.twig',
            [
                'specifiedConsentList' => $specifiedConsentList,
                'locale' => $locale,
                'displayName' => $displayName,
                'logo' => $logo,
            ]
        ));
    }

    public function deleteAction(string $serviceEntityId): Response
    {
        $this->guard->userIsLoggedIn();
        if (!$this->removeConsentEnabled) {
            throw new ResourceNotFoundException('The remove consent action is disabled');
        }

        $this->logger->notice(
            sprintf('User wants to retract consent from a service with Entity ID: %s', $serviceEntityId)
        );
        $user = $this->authenticatedUserProvider->getCurrentUser();
        $result = $this->specifiedConsentListService->deleteServiceWith($user, $serviceEntityId);
        $this->logger->notice(sprintf('Removing consent %s', ($result ? 'succeeded' : 'failed')));
        return new RedirectResponse($this->urlGenerator->generate('profile.my_services_overview'));
    }
}
