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
use OpenConext\Profile\Value\Locale;
use OpenConext\ProfileBundle\Profile\Command\ChangeLocaleCommand;

final class UserService
{
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

    public function __construct(
        UserRepository $userRepository,
        AuthenticatedUserProviderInterface $authenticatedUserProvider,
        LocaleService $localeService
    ) {
        $this->userRepository            = $userRepository;
        $this->authenticatedUserProvider = $authenticatedUserProvider;
        $this->localeService             = $localeService;
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
}
