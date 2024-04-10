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
    public function __construct(
        private readonly ServiceProvider $serviceProvider,
        private readonly IdentityProvider $identityProvider,
        private readonly RedirectBinding $redirectBinding,
        private readonly PostBinding $postBinding,
        private readonly StateHandler $stateHandler,
    ) {
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
            $this->identityProvider,
        );

        $this->stateHandler->setRequestId($authnRequest->getRequestId());

        return $this->redirectBinding->createResponseFor($authnRequest);
    }

    /**
     * @return \SAML2\Assertion
     */
    public function processSamlResponse(Request $request)
    {
        /** @var \SAML2\Assertion $assertion */
        $assertion = $this->postBinding->processResponse(
            $request,
            $this->identityProvider,
            $this->serviceProvider,
        );

        $this->stateHandler->clearRequestId();

        return $assertion;
    }

    /**
     * Resets the SAML flow.
     */
    public function reset(): void
    {
        $this->stateHandler->clearRequestId();
    }
}
