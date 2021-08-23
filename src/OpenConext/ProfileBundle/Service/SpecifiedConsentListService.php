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
use OpenConext\Profile\Entity\AuthenticatedUser;
use OpenConext\Profile\Value\SpecifiedConsentList;

class SpecifiedConsentListService
{
    /**
     * @var ConsentService
     */
    private $consentService;

    /**
     * @var AttributeReleasePolicyService
     */
    private $attributeReleasePolicyService;

    /**
     * @param ConsentService $consentService
     * @param AttributeReleasePolicyService $attributeReleasePolicyService
     */
    public function __construct(
        ConsentService $consentService,
        AttributeReleasePolicyService $attributeReleasePolicyService
    ) {
        $this->consentService                = $consentService;
        $this->attributeReleasePolicyService = $attributeReleasePolicyService;
    }

    public function getListFor(AuthenticatedUser $user): SpecifiedConsentList
    {
        $consentList = $this->consentService->findAllFor($user);

        // There is an off chance the user didn't give consent yet, in that case the consent list is null. It's not
        // possible to apply ARP on empty consent list.
        if (is_null($consentList)) {
            // So return an empty SpecifiedConsentList
            return SpecifiedConsentList::createWith([]);
        }

        return $this->attributeReleasePolicyService->applyAttributeReleasePolicies(
            $consentList,
            $user->getAttributesFiltered()
        );
    }

    public function deleteServiceFor(string $userId): bool
    {
        return $this->consentService->deleteServiceFor($userId);
    }
}
