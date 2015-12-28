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
use OpenConext\Profile\Value\SpecifiedConsent;
use OpenConext\Profile\Value\SpecifiedConsentList;
use OpenConext\Profile\Entity\AuthenticatedUser;
use Surfnet\SamlBundle\SAML2\Attribute\Filter\AttributeFilter;

class SpecifiedConsentListService
{
    /**
     * @var ConsentService
     */
    private $consentService;

    /**
     * @var AttributeFilter
     */
    private $attributeFilter;

    /**
     * @param ConsentService $consentService
     * @param AttributeFilter $attributeFilter
     */
    public function __construct(
        ConsentService $consentService,
        AttributeFilter $attributeFilter
    ) {
        $this->consentService  = $consentService;
        $this->attributeFilter = $attributeFilter;
    }

    /**
     * @param AuthenticatedUser $user
     * @return SpecifiedConsentList
     */
    public function getListFor(AuthenticatedUser $user)
    {
        $consentList        = $this->consentService->findAllFor($user);
        $filteredAttributes = $user->getAttributes()->apply($this->attributeFilter);

        $specifiedConsents = $consentList->map(function (Consent $consent) use ($filteredAttributes) {
            return SpecifiedConsent::specifies($consent, $filteredAttributes);
        });

        return SpecifiedConsentList::createWith($specifiedConsents);
    }
}
