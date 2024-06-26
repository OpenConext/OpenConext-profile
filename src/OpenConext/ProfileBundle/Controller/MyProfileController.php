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

use OpenConext\ProfileBundle\Service\UserService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyProfileController extends AbstractController
{

    public function __construct(
        private readonly UserService $userService,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route(
        path: "/my-profile",
        name: "profile.my_profile_overview",
        methods: ["GET"],
        schemes: "https",
    )]
    public function overview(): Response
    {
        $this->logger->info('Showing My Profile page');

        $user = $this->userService->getUser();

        return $this->render(
            '@OpenConextProfile/MyProfile/overview.html.twig',
            ['user' => $user],
        );
    }
}
