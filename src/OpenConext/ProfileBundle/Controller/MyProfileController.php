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
use OpenConext\ProfileBundle\Service\WayfResetLinkBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class MyProfileController extends AbstractController
{

    public function __construct(
        private readonly UserService $userService,
        private readonly LoggerInterface $logger,
        private readonly WayfResetLinkBuilder $wayfResetLinkBuilder,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly string $wayfResetUrl,
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
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
            [
                'user' => $user,
            ],
        );
    }

    #[Route(
        path: "/my-profile/wayf-reset",
        name: "profile.my_profile_wayf_reset",
        methods: ["POST"],
        schemes: "https",
    )]
    public function resetWayfChoice(
        Request $request,
    ): RedirectResponse {
        $token = new CsrfToken('wayf_reset', (string) $request->request->get('_token'));

        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new AccessDeniedHttpException('Invalid CSRF token for the WAYF reset action');
        }

        $this->logger->info('Redirecting to EngineBlock to reset the remembered WAYF choice');

        $wayfResetLink = $this->wayfResetLinkBuilder->build(
            $this->wayfResetUrl,
            $this->urlGenerator->generate('profile.my_profile_overview', [], UrlGeneratorInterface::ABSOLUTE_URL),
        );

        return new RedirectResponse($wayfResetLink);
    }
}
