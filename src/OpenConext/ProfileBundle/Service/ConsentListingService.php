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

namespace OpenConext\ProfileBundle\Service;

use OpenConext\Profile\Value\Consent;
use OpenConext\Profile\Value\ConsentList;
use OpenConext\ProfileBundle\Exception\RuntimeException;
use OpenConext\ProfileBundle\Security\Authentication\Entity\User;
use OpenConext\ProfileBundle\User\UserProvider;
use Surfnet\SamlBundle\SAML2\Attribute\Filter\AttributeFilter;

class ConsentListingService
{
    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * @var ConsentService
     */
    private $consentService;

    /**
     * @var AttributeFilter
     */
    private $attributeFilter;

    /**
     * @param UserProvider $userProvider
     * @param ConsentService $consentService
     * @param AttributeFilter $attributeFilter
     */
    public function __construct(
        UserProvider $userProvider,
        ConsentService $consentService,
        AttributeFilter $attributeFilter
    ) {
        $this->userProvider    = $userProvider;
        $this->consentService  = $consentService;
        $this->attributeFilter = $attributeFilter;
    }

    /**
     * @return ConsentList
     */
    public function getConsentListing()
    {
        if (!$this->userProvider->hasCurrentUser()) {
            throw new RuntimeException('Cannot get consent listing: no current user available');
        }

        $user               = $this->userProvider->getCurrentUser();
        $consentList        = $this->consentService->findAllFor($user);
        $filteredAttributes = $user->getAttributes()->apply($this->attributeFilter);

        $newConsents = [];

        /** @var Consent $consent */
        foreach ($consentList as $consent) {
            $newConsent = $consent->givenFor($filteredAttributes);
            $newConsents[] = $newConsent;
        }

        return new ConsentList($newConsents);
    }
}
