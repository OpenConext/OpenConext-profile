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

namespace OpenConext\ProfileBundle\Profile\Repository;

use OpenConext\Profile\Api\User;
use OpenConext\Profile\Repository\UserRepository as UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var SessionBagInterface
     */
    private $namespacedAttributeBag;

    public function __construct(NamespacedAttributeBag $namespacedAttributeBag)
    {
        $this->namespacedAttributeBag = $namespacedAttributeBag;
    }

    /**
     * @return User
     */
    public function findUser()
    {
        if (!$this->namespacedAttributeBag->has('user')) {
            return null;
        }

        return unserialize($this->namespacedAttributeBag->get('user'));
    }

    /**
     * @param User $user
     * @return void
     */
    public function save(User $user)
    {
        $this->namespacedAttributeBag->set('user', serialize($user));
    }
}
