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

use OpenConext\Profile\Api\AuthenticatedUserProviderInterface;
use OpenConext\Profile\Entity\AuthenticatedUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class AuthenticatedUserProvider implements AuthenticatedUserProviderInterface
{

    public function __construct(
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    public function getCurrentUser(): UserInterface
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}
