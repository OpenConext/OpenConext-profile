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
use OpenConext\ProfileBundle\Service\UserService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

class MyProfileController
{
    /**
     * @var UserService
     */
    private $userService;

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
     * @param UserService $userService
     * @param EngineInterface $templateEngine
     * @param Guard $guard
     * @param LoggerInterface $logger
     */
    public function __construct(
        UserService $userService,
        EngineInterface $templateEngine,
        Guard $guard,
        LoggerInterface $logger
    ) {
        $this->userService    = $userService;
        $this->templateEngine = $templateEngine;
        $this->guard          = $guard;
        $this->logger         = $logger;
    }

    /**
     * @return Response
     */
    public function overviewAction()
    {
        $this->guard->userIsLoggedIn();

        $this->logger->notice('Showing My Profile page');

        $user = $this->userService->getUser();

        return new Response($this->templateEngine->render(
            'OpenConextProfileBundle:MyProfile:overview.html.twig',
            ['user' => $user]
        ));
    }
}
