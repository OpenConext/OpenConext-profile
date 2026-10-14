<?php

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

declare(strict_types = 1);

namespace OpenConext\ProfileBundle\Tests\Security;

use Surfnet\SamlBundle\Security\Authentication\Handler\SuccessHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SamlAuthenticationSuccessHandlerTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return \OpenConext\Kernel::class;
    }

    public function test_the_success_handler_is_configured_with_the_saml_firewall_name(): void
    {
        self::bootKernel();

        /** @var SuccessHandler $successHandler */
        $successHandler = self::getContainer()->get(SuccessHandler::class);

        $this->assertSame('saml_based', $successHandler->getFirewallName());
    }

    public function test_a_deeplink_stored_before_login_is_used_as_the_post_login_redirect_target(): void
    {
        self::bootKernel();

        $deepLink = 'https://profile.surfconext.nl/attribute-support';

        $session = new Session(new MockArraySessionStorage());
        $session->set('_security.saml_based.target_path', $deepLink);

        $request = Request::create('https://profile.surfconext.nl/saml/acs', 'POST');
        $request->setSession($session);

        /** @var SuccessHandler $successHandler */
        $successHandler = self::getContainer()->get(SuccessHandler::class);

        $token = $this->createMock(TokenInterface::class);

        $response = $successHandler->onAuthenticationSuccess($request, $token);

        $this->assertNotNull($response);
        $this->assertTrue($response->isRedirect($deepLink));
    }

    public function test_without_a_stored_deeplink_the_default_target_path_is_used(): void
    {
        self::bootKernel();

        $session = new Session(new MockArraySessionStorage());

        $request = Request::create('https://profile.surfconext.nl/saml/acs', 'POST');
        $request->setSession($session);

        /** @var SuccessHandler $successHandler */
        $successHandler = self::getContainer()->get(SuccessHandler::class);

        $token = $this->createMock(TokenInterface::class);

        $response = $successHandler->onAuthenticationSuccess($request, $token);

        $this->assertNotNull($response);
        $this->assertTrue($response->isRedirect('https://profile.surfconext.nl/'));
    }
}
