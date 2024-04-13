<?php

declare(strict_types = 1);

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
use Symfony\Component\Security\Core\User\UserInterface;

class SpecifiedConsentListService
{
    public function __construct(
        private readonly ConsentService $consentService,
        private readonly AttributeReleasePolicyService $attributeReleasePolicyService,
    ) {
    }

    public function getListFor(
        UserInterface $user,
    ): SpecifiedConsentList {
        assert($user instanceof AuthenticatedUser);

        $consentList = $this->consentService->findAllFor($user);

        return $this->attributeReleasePolicyService->applyAttributeReleasePolicies(
            $consentList,
            $user->getAttributesFiltered(),
        );
    }

    public function deleteServiceWith(
        UserInterface $user,
        string $serviceEntityId,
    ): bool {
        assert($user instanceof AuthenticatedUser);

        return $this->consentService->deleteServiceWith($user, $serviceEntityId);
    }
}
