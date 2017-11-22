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

use OpenConext\Profile\Value\AttributeAggregation\AttributeAggregationEnabledAttributes;
use OpenConext\ProfileBundle\Security\Guard;
use OpenConext\ProfileBundle\Service\AttributeAggregationService;
use OpenConext\ProfileBundle\Service\AuthenticatedUserProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
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
     * @var AuthenticatedUserProvider
     */
    private $userProvider;

    /**
     * @param EngineInterface $templateEngine
     * @param Guard $guard
     * @param LoggerInterface $logger
     * @param AttributeAggregationService $service
     * @param AuthenticatedUserProvider $userProvider
     */
    public function __construct(
        EngineInterface $templateEngine,
        Guard $guard,
        LoggerInterface $logger,
        AttributeAggregationService $service,
        AuthenticatedUserProvider $userProvider
    ) {
        $this->templateEngine = $templateEngine;
        $this->guard = $guard;
        $this->logger = $logger;
        $this->service = $service;
        $this->userProvider = $userProvider;
    }

    /**
     * @return Response
     */
    public function overviewAction()
    {
        $this->guard->userIsLoggedIn();
        $this->logger->notice('Showing My Connections page');


        $user = $this->userProvider->getCurrentUser();
        $attributes = $this->service->findByUser($user);

        return new Response($this->templateEngine->render(
            'OpenConextProfileBundle:MyConnections:overview.html.twig',
            ['orcid' => $attributes->getAttributes()[0]]
        ));
    }
}
