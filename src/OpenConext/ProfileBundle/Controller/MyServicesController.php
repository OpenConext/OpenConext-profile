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

use OpenConext\Profile\Api\AuthenticatedUserProvider;
use OpenConext\ProfileBundle\Service\SpecifiedConsentListService;
use OpenConext\ProfileBundle\User\UserProvider;
use OpenConext\ProfileBundle\Security\Guard;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Templating\EngineInterface;

class MyServicesController
{
    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * @var SpecifiedConsentListService
     */
    private $specifiedConsentListService;

    /**
     * @var AuthenticatedUserProvider
     */
    private $authenticatedUserRepository;

    /**
     * @var Guard
     */
    private $guard;

    /**
     * @param EngineInterface $templateEngine
     * @param AuthenticatedUserProvider $authenticatedUserRepository
     * @param SpecifiedConsentListService $specifiedConsentListService
     * @param Guard $guard
     */
    public function __construct(
        EngineInterface $templateEngine,
        AuthenticatedUserProvider $authenticatedUserRepository,
        SpecifiedConsentListService $specifiedConsentListService,
        Guard $guard
    ) {
        $this->templateEngine              = $templateEngine;
        $this->authenticatedUserRepository = $authenticatedUserRepository;
        $this->specifiedConsentListService = $specifiedConsentListService;
        $this->guard                       = $guard;
    }

    public function overviewAction()
    {
        $this->guard->userIsLoggedIn();

        $user = $this->authenticatedUserRepository->getCurrentUser();
        $specifiedConsentList = $this->specifiedConsentListService->getListFor($user);

        return new Response($this->templateEngine->render(
            'OpenConextProfileBundle:MyServices:overview.html.twig',
            ['specifiedConsentList' => $specifiedConsentList]
        ));
    }
}
