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
    /**
     * @var SupportContactEmailService
     */
    private $supportContactEmailService;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var AuthenticatedUserProviderInterface
     */
    private $authenticatedUserProvider;

    /**
     * @var LocaleService
     */
    private $localeService;

    /**
     * @var EntityId
     */
    private $engineBlockEntityId;

    /**
     * @var UserLifecycleApiClient
     */
    private $userLifecycleApiClient;

    public function __construct(
        SupportContactEmailService $supportContactEmailService,
        UserRepositoryInterface $userRepository,
        AuthenticatedUserProviderInterface $authenticatedUserProvider,
        LocaleService $localeService,
        EntityId $engineBlockEntityId,
    ) {
        $this->supportContactEmailService = $supportContactEmailService;
        $this->userRepository             = $userRepository;
        $this->authenticatedUserProvider  = $authenticatedUserProvider;
        $this->localeService              = $localeService;
        $this->engineBlockEntityId        = $engineBlockEntityId;
    }

    /**
     * The user lifecycle client is optional.
     *
     * @param UserLifecycleApiClient $client
     */
    public function setUserLifecycleApiClient(UserLifecycleApiClient $client = null)
    {
        $this->userLifecycleApiClient = $client;
    }

    /**
     * @return ApiUserInterface
     */
    public function getUser()
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

    /**
     * @param ChangeLocaleCommand $changeLocaleCommand
     */
    public function changeLocale(ChangeLocaleCommand $changeLocaleCommand)
    {
        $user = $this->getUser();
        $user->switchLocaleTo(new Locale($changeLocaleCommand->newLocale));

        $this->localeService->saveLocaleOf($user);
    }

    /**
     * @return array
     */
    public function getUserLifecycleData()
    {
        if (!$this->userLifecycleApiIsEnabled()) {
            return;
        }

        $user = $this->getUser();

        return $this->userLifecycleApiClient->read(
            sprintf('/api/deprovision/%s', $user->getId()),
        );
    }

    /**
     * @return bool
     */
    public function userLifecycleApiIsEnabled()
    {
        return $this->userLifecycleApiClient !== null;
    }

    /**
     * @param ApiUserInterface $user
     * @return ApiUserInterface
     */
    private function enrichUserWithSupportContactEmail(ApiUserInterface $user)
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
     * @return null|EntityId
     */
    private function getNearestAuthenticatingAuthorityEntityId(array $entityIds)
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
