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

namespace OpenConext\ProfileBundle\Security\Firewall;

use Exception;
use OpenConext\ProfileBundle\Saml\StateHandler;
use OpenConext\ProfileBundle\Security\Authentication\SamlInteractionProvider;
use OpenConext\ProfileBundle\Security\Authentication\Token\SamlToken;
use SAML2\Response\Exception\PreconditionNotMetException;
use Surfnet\SamlBundle\Http\Exception\AuthnFailedSamlResponseException;
use Surfnet\SamlBundle\SAML2\Response\Assertion\InResponseTo;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use \Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;
use Twig\Environment as Twig;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SamlListener
{
    /**
     * @var \Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface
     */
    private $authenticationManager;

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly UrlMatcherInterface $urlMatcher,
        private readonly TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        private readonly SamlInteractionProvider $samlInteractionProvider,
        private readonly StateHandler $stateHandler,
        /**
         * @var \Surfnet\SamlBundle\Monolog\SamlAuthenticationLogger
         */
        private LoggerInterface $logger,
        private readonly Twig $twig,
    ) {
        $this->authenticationManager    = $authenticationManager;
    }

    public function __invoke(RequestEvent $event): void
    {
        dd('SamlListener Invoked');
        try {
            $this->handleEvent($event);
        } catch (Exception $e) {
            $this->samlInteractionProvider->reset();
            throw $e;
        }
    }

    private function handleEvent(RequestEvent $event): void
    {
        if ($this->tokenStorage->getToken()) {
            return;
        }

        if (!$this->isAcsRequest($event->getRequest()) ||
            !$this->samlInteractionProvider->isSamlAuthenticationInitiated()) {
            $this->sendAuthnRequest($event);

            return;
        }

        $expectedInResponseTo = $this->stateHandler->getRequestId();
        $logger = $this->logger;

        try {
            $assertion = $this->samlInteractionProvider->processSamlResponse($event->getRequest());
        } catch (PreconditionNotMetException $e) {
            $logger->notice(sprintf('SAML response precondition not met: "%s"', $e->getMessage()));
            $this->setPreconditionExceptionResponse($e, $event);

            return;
        } catch (Exception $e) {
            $logger->error(sprintf('Failed SAMLResponse Parsing: "%s"', $e->getMessage()));
            throw new AuthenticationException('Failed SAMLResponse parsing', 0, $e);
        }

        if (!InResponseTo::assertEquals($assertion, $expectedInResponseTo)) {
            $logger->error('Unknown or unexpected InResponseTo in SAMLResponse');
            throw new AuthenticationException('Unknown or unexpected InResponseTo in SAMLResponse');
        }

        $logger->info('Successfully processed SAMLResponse, attempting to authenticate');

        $token = new SamlToken();
        $token->assertion = $assertion;

        try {
            $authToken = $this->authenticationManager->authenticate($token);
        } catch (AuthenticationException $failed) {
            $logger->error(sprintf('Authentication Failed, reason: "%s"', $failed->getMessage()));
            $this->setAuthenticationFailedResponse($event);

            return;
        }

        $this->tokenStorage->setToken($authToken);

        // migrate the Sion to prevent session hijacking
        $this->requestStack->getSession()->migrate();

        $event->setResponse(new RedirectResponse($this->stateHandler->getCurrentRequestUri()));
        $logger->notice(
            'Authentication succeeded, redirecting to original location',
            ['user' => (string)$authToken->getUser()],
        );
    }

    /**
     * Check if this is a request to the ACS location.
     *
     * @return bool
     */
    private function isAcsRequest(Request $request): bool
    {
        try {
            $params = $this->urlMatcher->match($request->getPathInfo());
        } catch (ResourceNotFoundException) {
            return false;
        }

        return $params['_route'] === 'profile.saml_consume_assertion';
    }

    private function sendAuthnRequest(RequestEvent $event): void
    {
        $this->stateHandler->setCurrentRequestUri($event->getRequest()->getUri());

        $event->setResponse($this->samlInteractionProvider->initiateSamlRequest());

        $logger = $this->logger;
        $logger->info('Sending AuthnRequest');
    }

    private function setPreconditionExceptionResponse(PreconditionNotMetException $exception, RequestEvent $event): void
    {
        $template = null;

        if ($exception instanceof AuthnFailedSamlResponseException) {
            $template = 'SurfnetStepupSelfServiceSelfServiceBundle:Saml/Exception:authnFailed.html.twig';
        } else {
            $template = 'SurfnetStepupSelfServiceSelfServiceBundle:Saml/Exception:preconditionNotMet.html.twig';
        }

        $html = $this->twig->render($template, ['exception' => $exception]);
        $event->setResponse(new Response($html, Response::HTTP_UNAUTHORIZED));
    }

    /**
     * Deny authentication by default
     */
    private function setAuthenticationFailedResponse(RequestEvent $event): void
    {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
    }
}
