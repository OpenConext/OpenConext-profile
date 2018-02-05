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

namespace OpenConext\ProfileBundle\Security\Authentication;

use OpenConext\ProfileBundle\Saml\StateHandler;
use Surfnet\SamlBundle\Entity\IdentityProvider;
use Surfnet\SamlBundle\Entity\ServiceProvider;
use Surfnet\SamlBundle\Http\PostBinding;
use Surfnet\SamlBundle\Http\RedirectBinding;
use Surfnet\SamlBundle\SAML2\AuthnRequestFactory;
use Symfony\Component\HttpFoundation\Request;

class SamlInteractionProvider
{
    /**
     * @var \Surfnet\SamlBundle\Entity\ServiceProvider
     */
    private $serviceProvider;

    /**
     * @var \Surfnet\SamlBundle\Entity\IdentityProvider
     */
    private $identityProvider;

    /**
     * @var \Surfnet\SamlBundle\Http\RedirectBinding
     */
    private $redirectBinding;

    /**
     * @var \Surfnet\SamlBundle\Http\PostBinding
     */
    private $postBinding;

    /**
     * @var \OpenConext\ProfileBundle\Saml\StateHandler
     */
    private $stateHandler;

    public function __construct(
        ServiceProvider $serviceProvider,
        IdentityProvider $identityProvider,
        RedirectBinding $redirectBinding,
        PostBinding $postBinding,
        StateHandler $sessionHandler
    ) {
        $this->serviceProvider = $serviceProvider;
        $this->identityProvider = $identityProvider;
        $this->redirectBinding = $redirectBinding;
        $this->postBinding = $postBinding;
        $this->stateHandler = $sessionHandler;
    }

    /**
     * @return bool
     */
    public function isSamlAuthenticationInitiated()
    {
        return $this->stateHandler->hasRequestId();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function initiateSamlRequest()
    {
        $authnRequest = AuthnRequestFactory::createNewRequest(
            $this->serviceProvider,
            $this->identityProvider
        );

        $this->stateHandler->setRequestId($authnRequest->getRequestId());

        return $this->redirectBinding->createRedirectResponseFor($authnRequest);
    }

    /**
     * @param Request $request
     * @return \SAML2\Assertion
     */
    public function processSamlResponse(Request $request)
    {
        /** @var \SAML2\Assertion $assertion */
        $assertion = $this->postBinding->processResponse(
            $request,
            $this->identityProvider,
            $this->serviceProvider
        );

        $this->stateHandler->clearRequestId();

        return $assertion;
    }

    /**
     * Resets the SAML flow.
     */
    public function reset()
    {
        $this->stateHandler->clearRequestId();
    }
}
