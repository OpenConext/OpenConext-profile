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

use OpenConext\EngineBlockApiClient\Repository\InstitutionRepository;
use OpenConext\Profile\Api\AuthenticatedUserProviderInterface;
use OpenConext\ProfileBundle\Service\SpecifiedConsentListService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MyServicesController extends AbstractController
{
    public function __construct(
        private readonly AuthenticatedUserProviderInterface $authenticatedUserProvider,
        private readonly SpecifiedConsentListService        $specifiedConsentListService,
        private readonly UrlGeneratorInterface              $urlGenerator,
        private readonly LoggerInterface                    $logger,
        private readonly InstitutionRepository              $institutionRepository,
        private readonly bool                               $removeConsentEnabled,
    ) {
    }

    #[Route(
        path: '/my-services',
        name: 'profile.my_services_overview',
        methods: ['GET'],
        schemes: ['https'],
    )]
    public function overview(
        Request $request,
    ): Response {
        $this->logger->info('User requested My Services page');

        $locale = $request->getLocale();
        $user = $this->authenticatedUserProvider->getCurrentUser();
        $specifiedConsentList = $this->specifiedConsentListService->getListFor($user);
        $specifiedConsentList->sortByDisplayName($locale);

        $this->logger->info(sprintf('Showing %s services on My Services page', count($specifiedConsentList)));

        $organization = $this->institutionRepository->getOrganizationAndLogoForIdp($user);

        return $this->render(
            '@OpenConextProfile/MyServices/overview.html.twig',
            [
                'specifiedConsentList' => $specifiedConsentList,
                'locale' => $locale,
                'displayName' => $organization->getDisplayName($locale),
                'logo' => $organization->getLogo(),
            ],
        );
    }

    #[Route(
        path: '/my-services/delete/{serviceEntityId}',
        name: 'profile.my_services_delete',
        requirements: ['serviceEntityId' => '.+'],
        methods: ['GET'],
        schemes: ['https'],
    )]
    public function delete(
        string $serviceEntityId,
    ): Response {
        if (!$this->removeConsentEnabled) {
            throw new ResourceNotFoundException('The remove consent action is disabled');
        }

        $this->logger->notice(
            sprintf('User wants to retract consent from a service with Entity ID: %s', $serviceEntityId),
        );
        $user = $this->authenticatedUserProvider->getCurrentUser();
        $result = $this->specifiedConsentListService->deleteServiceWith($user, $serviceEntityId);
        $this->logger->notice(sprintf('Removing consent %s', ($result ? 'succeeded' : 'failed')));

        return new RedirectResponse($this->urlGenerator->generate('profile.my_services_overview'));
    }
}
