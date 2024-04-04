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

use OpenConext\ProfileBundle\Security\Guard;
use OpenConext\ProfileBundle\Service\UserService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Twig\Environment;

class MySurfConextController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var Environment
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

    public function __construct(
        UserService $userService,
        Environment $templateEngine,
        Guard $guard,
        LoggerInterface $logger,
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

        $this->logger->info('Showing My SURFconext page');

        $user = $this->userService->getUser();

        return new Response($this->templateEngine->render(
            '@OpenConextProfile/MySurfConext/overview.html.twig',
            [
                'user' => $user,
                'userLifecycleIsEnabled' => $this->userService->userLifecycleApiIsEnabled(),
            ],
        ));
    }

    /**
     * @return Response
     */
    public function userDataDownloadAction()
    {
        $this->guard->userIsLoggedIn();

        if (!$this->userService->userLifecycleApiIsEnabled()) {
            throw new ResourceNotFoundException('User lifecycle API is disabled');
        }

        $this->logger->notice('Exporting user data from user lifecycle API');

        return new JsonResponse(
            $this->userService->getUserLifecycleData(),
            200,
            [
                'Content-Disposition' => 'attachment; filename="profile-user-data.json"'
            ],
        );
    }
}
