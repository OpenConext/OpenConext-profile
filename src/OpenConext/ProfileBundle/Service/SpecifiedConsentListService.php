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

use OpenConext\EngineBlockApiClientBundle\Service\AttributeReleasePolicyService;
use OpenConext\Profile\Value\Consent;
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
     * @var AttributeReleasePolicyService
     */
    private $attributeReleasePolicyService;

    /**
     * @param ConsentService $consentService
     * @param AttributeFilter $attributeFilter
     * @param AttributeReleasePolicyService $attributeReleasePolicyService
     */
    public function __construct(
        ConsentService $consentService,
        AttributeFilter $attributeFilter,
        AttributeReleasePolicyService $attributeReleasePolicyService
    ) {
        $this->consentService                = $consentService;
        $this->attributeFilter               = $attributeFilter;
        $this->attributeReleasePolicyService = $attributeReleasePolicyService;
    }

    /**
     * @param AuthenticatedUser $user
     * @return SpecifiedConsentList
     */
    public function getListFor(AuthenticatedUser $user)
    {
        $consentList = $this->consentService->findAllFor($user);

        return $this->attributeReleasePolicyService->applyAttributeReleasePolicies(
            $consentList,
            $user->getAttributes()
        );
    }
}
