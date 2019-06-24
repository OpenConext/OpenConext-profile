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

use OpenConext\Profile\Api\AuthenticatedUserProviderInterface;
use OpenConext\ProfileBundle\Service\SpecifiedConsentListService;
use OpenConext\ProfileBundle\Security\Guard;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
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
     * @var AuthenticatedUserProviderInterface
     */
    private $authenticatedUserProvider;

    /**
     * @var Guard
     */
    private $guard;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param EngineInterface $templateEngine
     * @param AuthenticatedUserProviderInterface $authenticatedUserProvider
     * @param SpecifiedConsentListService $specifiedConsentListService
     * @param Guard $guard
     * @param LoggerInterface $logger
     */
    public function __construct(
        EngineInterface $templateEngine,
        AuthenticatedUserProviderInterface $authenticatedUserProvider,
        SpecifiedConsentListService $specifiedConsentListService,
        Guard $guard,
        LoggerInterface $logger
    ) {
        $this->templateEngine              = $templateEngine;
        $this->authenticatedUserProvider   = $authenticatedUserProvider;
        $this->specifiedConsentListService = $specifiedConsentListService;
        $this->guard                       = $guard;
        $this->logger                      = $logger;
    }

    public function overviewAction()
    {
        $this->guard->userIsLoggedIn();

        $this->logger->notice('User requested My Services page');

        $user                 = $this->authenticatedUserProvider->getCurrentUser();
        $specifiedConsentList = $this->specifiedConsentListService->getListFor($user);

        $this->logger->notice(sprintf('Showing %s services on My Services page', count($specifiedConsentList)));

        return new Response($this->templateEngine->render(
            'OpenConextProfileBundle:MyServices:overview.html.twig',
            ['specifiedConsentList' => $specifiedConsentList]
        ));
    }
}
