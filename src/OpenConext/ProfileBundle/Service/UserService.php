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

use OpenConext\Profile\Entity\User;
use OpenConext\Profile\Api\AuthenticatedUserProvider as AuthenticatedUserProviderInterface;
use OpenConext\Profile\Repository\UserRepository;
use OpenConext\Profile\Value\EntityId;
use OpenConext\Profile\Value\Locale;
use OpenConext\ProfileBundle\Profile\Command\ChangeLocaleCommand;

final class UserService
{
    /**
     * @var SupportContactEmailService
     */
    private $supportContactEmailService;

    /**
     * @var UserRepository
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

    public function __construct(
        SupportContactEmailService $supportContactEmailService,
        UserRepository $userRepository,
        AuthenticatedUserProviderInterface $authenticatedUserProvider,
        LocaleService $localeService,
        EntityId $engineBlockEntityId
    ) {
        $this->supportContactEmailService = $supportContactEmailService;
        $this->userRepository             = $userRepository;
        $this->authenticatedUserProvider  = $authenticatedUserProvider;
        $this->localeService              = $localeService;
        $this->engineBlockEntityId        = $engineBlockEntityId;
    }

    /**
     * @return User
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
     * @param User $user
     * @return User
     */
    private function enrichUserWithSupportContactEmail(User $user)
    {
        $entityIds                 = $this->authenticatedUserProvider->getCurrentUser()->getAuthenticatingAuthorities();
        $authenticatingIdpEntityId = $this->getNearestAuthenticatingAuthorityEntityId($entityIds);

        if ($authenticatingIdpEntityId === null) {
            return $user;
        }

        $supportContactEmail = $this->supportContactEmailService->findSupportContactEmailForIdp(
            $authenticatingIdpEntityId
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
