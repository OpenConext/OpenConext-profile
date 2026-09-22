<?php

declare(strict_types = 1);

/**
 * Copyright 2026 SURFnet B.V.
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

namespace OpenConext\ProfileBundle\Tests\Controller;

use OpenConext\Profile\Api\AuthenticatedUserProviderInterface;
use OpenConext\Profile\Repository\ContactPersonRepositoryInterface;
use OpenConext\Profile\Repository\LocaleRepositoryInterface;
use OpenConext\Profile\Repository\UserRepositoryInterface;
use OpenConext\Profile\Value\EntityId;
use OpenConext\Profile\Value\Locale;
use OpenConext\Profile\Value\LocaleSet;
use OpenConext\ProfileBundle\Controller\MyProfileController;
use OpenConext\ProfileBundle\Service\LocaleService;
use OpenConext\ProfileBundle\Service\SupportContactEmailService;
use OpenConext\ProfileBundle\Service\UserService;
use OpenConext\ProfileBundle\Service\WayfResetLinkBuilder;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class MyProfileControllerTest extends TestCase
{
    /**
     * @test
     * @group Controller
     */
    public function it_redirects_to_the_wayf_reset_url_when_the_csrf_token_is_valid(): void
    {
        $csrfTokenManager = $this->createMock(CsrfTokenManagerInterface::class);
        $csrfTokenManager->expects($this->once())
            ->method('isTokenValid')
            ->with($this->equalTo(new CsrfToken('wayf_reset', 'valid-token')))
            ->willReturn(true);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->expects($this->once())
            ->method('generate')
            ->with('profile.my_profile_overview', [], UrlGeneratorInterface::ABSOLUTE_URL)
            ->willReturn('https://profile.example.org/my-profile');

        $controller = new MyProfileController(
            $this->createUserService(),
            $this->createMock(LoggerInterface::class),
            new WayfResetLinkBuilder(),
            $urlGenerator,
            'https://engine.example.org/reset-remember-wayf',
            $csrfTokenManager,
        );

        $response = $controller->resetWayfChoice(new Request([], ['_token' => 'valid-token']));

        $this->assertSame(
            'https://engine.example.org/reset-remember-wayf?redirect=https%3A%2F%2Fprofile.example.org%2Fmy-profile',
            $response->getTargetUrl(),
        );
    }

    /**
     * @test
     * @group Controller
     */
    public function it_denies_access_when_the_csrf_token_is_invalid(): void
    {
        $csrfTokenManager = $this->createMock(CsrfTokenManagerInterface::class);
        $csrfTokenManager->expects($this->once())
            ->method('isTokenValid')
            ->with($this->equalTo(new CsrfToken('wayf_reset', 'invalid-token')))
            ->willReturn(false);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->expects($this->never())->method('generate');

        $controller = new MyProfileController(
            $this->createUserService(),
            $this->createMock(LoggerInterface::class),
            new WayfResetLinkBuilder(),
            $urlGenerator,
            'https://engine.example.org/reset-remember-wayf',
            $csrfTokenManager,
        );

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Invalid CSRF token for the WAYF reset action');

        $controller->resetWayfChoice(new Request([], ['_token' => 'invalid-token']));
    }

    private function createUserService(): UserService
    {
        return new UserService(
            new SupportContactEmailService($this->createMock(ContactPersonRepositoryInterface::class)),
            $this->createMock(UserRepositoryInterface::class),
            $this->createMock(AuthenticatedUserProviderInterface::class),
            new LocaleService(
                $this->createMock(LocaleRepositoryInterface::class),
                LocaleSet::create([new Locale('en')]),
                new Locale('en'),
            ),
            new EntityId('https://engine.example.org/authentication/sp/metadata'),
        );
    }
}
