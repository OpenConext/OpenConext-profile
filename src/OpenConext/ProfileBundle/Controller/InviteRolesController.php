<?php

declare(strict_types = 1);

/**
 * Copyright 2024 SURFnet B.V.
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
use OpenConext\Profile\Repository\InviteRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class InviteRolesController extends AbstractController
{
    public function __construct(
        private readonly bool $enabled,
        private readonly InviteRepositoryInterface $inviteRepository,
        private readonly AuthenticatedUserProviderInterface $userProvider,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route(
        path: '/invite-roles',
        name: 'profile.invite_roles',
        methods: ['GET'],
        schemes: ['https'],
    )]
    public function __invoke(): Response
    {
        if (!$this->enabled) {
            throw $this->createAccessDeniedException();
        }
        $this->logger->info('Showing the OpenConext-Invite roles page');

        $user = $this->userProvider->getCurrentUser();
        $inviteRoles = $this->inviteRepository->findAllFor($user->getUserIdentifier());
        return $this->render('@OpenConextProfile/InviteRoles/overview.html.twig', ['inviteRoles' => $inviteRoles]);
    }
}
