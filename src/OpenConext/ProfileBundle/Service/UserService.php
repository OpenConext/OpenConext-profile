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

use OpenConext\Profile\Api\ApiUserInterface;
use OpenConext\Profile\Entity\User;
use OpenConext\Profile\Api\AuthenticatedUserProviderInterface;
use OpenConext\Profile\Repository\UserRepositoryInterface;
use OpenConext\Profile\Value\EntityId;
use OpenConext\Profile\Value\Locale;
use OpenConext\ProfileBundle\Profile\Command\ChangeLocaleCommand;
use OpenConext\UserLifecycleApiClientBundle\Http\JsonApiClient as UserLifecycleApiClient;

final class UserService
{
    private ?UserLifecycleApiClient $userLifecycleApiClient = null;

    public function __construct(
        private readonly SupportContactEmailService $supportContactEmailService,
        private readonly UserRepositoryInterface $userRepository,
        private readonly AuthenticatedUserProviderInterface $authenticatedUserProvider,
        private readonly LocaleService $localeService,
        private readonly EntityId $engineBlockEntityId,
    ) {
    }

    /**
     * The user lifecycle client is optional.
     */
    public function setUserLifecycleApiClient(?UserLifecycleApiClient $client = null): void
    {
        $this->userLifecycleApiClient = $client;
    }

    public function getUser(): ApiUserInterface
    {
        $user = $this->userRepository->findUser();

        if ($user) {
            return $user;
        }

        $user = new User($this->authenticatedUserProvider->getCurrentUser(), $this->localeService->getLocale());
        $user = $this->enrichUserWithSupportContactEmail($user);

        $this->userRepository->save($user);

        return $user;
    }

    public function changeLocale(ChangeLocaleCommand $changeLocaleCommand): void
    {
        $user = $this->getUser();
        $user->switchLocaleTo(new Locale($changeLocaleCommand->newLocale));

        $this->localeService->saveLocaleOf($user);
    }

    public function getUserLifecycleData(): array
    {
        if (!$this->userLifecycleApiIsEnabled()) {
            return [];
        }

        $user = $this->getUser();

        return $this->userLifecycleApiClient->read(
            sprintf('/api/deprovision/%s', $user->getId()),
        );
    }

    /**
     * @return bool
     */
    public function userLifecycleApiIsEnabled(): bool
    {
        return $this->userLifecycleApiClient !== null;
    }

    private function enrichUserWithSupportContactEmail(ApiUserInterface $user): ApiUserInterface
    {
        $entityIds                 = $this->authenticatedUserProvider->getCurrentUser()->getAuthenticatingAuthorities();
        $authenticatingIdpEntityId = $this->getNearestAuthenticatingAuthorityEntityId($entityIds);

        if ($authenticatingIdpEntityId === null) {
            return $user;
        }

        $supportContactEmail = $this->supportContactEmailService->findSupportContactEmailForIdp(
            $authenticatingIdpEntityId,
        );

        if ($supportContactEmail === null) {
            return $user;
        }

        return $user->withSupportContactEmail($supportContactEmail);
    }

    /**
     * @param EntityId[] $entityIds
     */
    private function getNearestAuthenticatingAuthorityEntityId(array $entityIds): ?EntityId
    {
        $lastEntityId = array_pop($entityIds);

        if ($lastEntityId === null) {
            return null;
        }

        if (!$this->engineBlockEntityId->equals($lastEntityId)) {
            return $lastEntityId;
        }

        return array_pop($entityIds);
    }
}
