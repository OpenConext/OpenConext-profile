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

use OpenConext\ProfileBundle\Service\UserService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Twig\Environment;

class MySurfConextController extends AbstractController
{

    public function __construct(
        private readonly UserService $userService,
        private readonly Environment $templateEngine,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route(
        path: "/my-surfconext",
        name: "profile.my_surf_conext_overview",
        methods: ["GET"],
        schemes: "https",
    )]
    public function overview(): Response
    {
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

    #[Route(
        path: "/my-surfconext/download",
        name: "profile.my_surf_conext_user_data_download",
        methods: ["GET"],
        schemes: "https",
    )]
    public function userDataDownload(): JsonResponse
    {
        if (!$this->userService->userLifecycleApiIsEnabled()) {
            throw new ResourceNotFoundException('User lifecycle API is disabled');
        }

        $this->logger->notice('Exporting user data from user lifecycle API');

        return new JsonResponse(
            $this->userService->getUserLifecycleData(),
            Response::HTTP_OK,
            [
                'Content-Disposition' => 'attachment; filename="profile-user-data.json"'
            ],
        );
    }
}
