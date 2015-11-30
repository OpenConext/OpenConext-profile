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
use OpenConext\ProfileBundle\Saml\StateHandler as SamlStateHandler;
use OpenConext\ProfileBundle\Security\Authentication\SamlInteractionProvider;
use OpenConext\ProfileBundle\Security\Authentication\Token\SamlToken;
use OpenConext\ProfileBundle\Transaction\StateHandler as TransactionStateHandler;
use SAML2_Response_Exception_PreconditionNotMetException as PreconditionNotMetException;
use Surfnet\SamlBundle\Http\Exception\AuthnFailedSamlResponseException;
use Surfnet\SamlBundle\SAML2\Response\Assertion\InResponseTo;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Twig_Environment as Twig;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SamlListener implements ListenerInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    private $session;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface
     */
    private $authenticationManager;

    /**
     * @var \OpenConext\ProfileBundle\Security\Authentication\SamlInteractionProvider
     */
    private $samlInteractionProvider;

    /**
     * @var \OpenConext\ProfileBundle\Saml\StateHandler
     */
    private $samlStateHandler;

    /**
     * @var TransactionStateHandler
     */
    private $transactionStateHandler;

    /**
     * @var \Surfnet\SamlBundle\Monolog\SamlAuthenticationLogger
     */
    private $logger;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @param Session $session
     * @param TokenStorageInterface $tokenStorage
     * @param AuthenticationManagerInterface $authenticationManager
     * @param SamlInteractionProvider $samlInteractionProvider
     * @param SamlStateHandler $samlStateHandler
     * @param TransactionStateHandler $transactionStateHandler
     * @param LoggerInterface $logger
     * @param Twig $twig
     */
    public function __construct(
        Session $session,
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        SamlInteractionProvider $samlInteractionProvider,
        SamlStateHandler $samlStateHandler,
        TransactionStateHandler $transactionStateHandler,
        LoggerInterface $logger,
        Twig $twig
    ) {
        $this->session                 = $session;
        $this->tokenStorage            = $tokenStorage;
        $this->authenticationManager   = $authenticationManager;
        $this->samlInteractionProvider = $samlInteractionProvider;
        $this->samlStateHandler        = $samlStateHandler;
        $this->transactionStateHandler = $transactionStateHandler;
        $this->logger                  = $logger;
        $this->twig                    = $twig;
    }

    public function handle(GetResponseEvent $event)
    {
        try {
            $this->handleEvent($event);
        } catch (\Exception $e) {
            $this->samlInteractionProvider->reset();
            throw $e;
        }
    }

    /**
     * @param GetResponseEvent $event
     */
    private function handleEvent(GetResponseEvent $event)
    {
        if ($this->tokenStorage->getToken()) {
            return;
        }

        if ($this->transactionStateHandler->tooManyAttempts()) {
            $this->offerRetryOption($event);

            return;
        }

        if (!$this->samlInteractionProvider->isSamlAuthenticationInitiated()) {
            $this->sendAuthnRequest($event);

            return;
        }

        if (!$event->getRequest()->request->has('SAMLResponse')) {
            $this->sendAuthnRequest($event);

            return;
        }

        $expectedInResponseTo = $this->samlStateHandler->getRequestId();
        $logger               = $this->logger;

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

        $logger->notice('Successfully processed SAMLResponse, attempting to authenticate');

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

        // migrate the session to prevent session hijacking
        $this->session->migrate();

        $event->setResponse(new RedirectResponse($this->samlStateHandler->getCurrentRequestUri()));
        $logger->notice('Authentication succeeded, redirecting to original location');
    }

    /**
     * @param GetResponseEvent $event
     */
    private function sendAuthnRequest(GetResponseEvent $event)
    {
        $this->samlStateHandler->setCurrentRequestUri($event->getRequest()->getUri());
        $this->transactionStateHandler->incrementAttempts();

        $event->setResponse($this->samlInteractionProvider->initiateSamlRequest());

        $logger = $this->logger;
        $logger->notice('Sending AuthnRequest');
    }

    /**
     * @param GetResponseEvent $event
     */
    private function offerRetryOption(GetResponseEvent $event)
    {
        $event->setResponse(new Response($this->twig->render(
            'OpenConextProfileBundle:Saml:too-many-attempts.html.twig'
        )));
    }

    /**
     * @param PreconditionNotMetException $exception
     * @param GetResponseEvent $event
     */
    private function setPreconditionExceptionResponse(PreconditionNotMetException $exception, GetResponseEvent $event)
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
     * @param GetResponseEvent $event
     */
    private function setAuthenticationFailedResponse(GetResponseEvent $event)
    {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
    }
}
