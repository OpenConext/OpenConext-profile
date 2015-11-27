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

namespace OpenConext\ProfileBundle\Transaction;

use OpenConext\Profile\Assert;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;

class StateHandler
{
    /**
     * @var NamespacedAttributeBag
     */
    private $attributeBag;
    private $allowedAttempts;

    /**
     * @param NamespacedAttributeBag $attributeBag
     * @param integer $allowedAttempts
     */
    public function __construct(NamespacedAttributeBag $attributeBag, $allowedAttempts)
    {
        Assert::integer($allowedAttempts);

        $this->attributeBag    = $attributeBag;
        $this->allowedAttempts = $allowedAttempts;
    }

    /**
     * @return bool
     */
    public function tooManyAttempts()
    {
        $attempts = $this->attributeBag->get('attempts', 0);

        if ($attempts > $this->allowedAttempts) {
            return true;
        }

        return false;
    }

    public function incrementAttempts()
    {
        $attempts = $this->attributeBag->get('attempts', 0);
        $this->attributeBag->set('attempts', $attempts + 1);
    }

    public function resetAttempts()
    {
        $this->attributeBag->set('attempts', 0);
    }
}
