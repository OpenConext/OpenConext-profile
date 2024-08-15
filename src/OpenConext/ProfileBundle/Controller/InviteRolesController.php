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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InviteRolesController extends AbstractController
{
    public function __construct(
        private readonly bool $enabled,
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

        return $this->render('@OpenConextProfile/InviteRoles/overview.html.twig',);
    }
}
