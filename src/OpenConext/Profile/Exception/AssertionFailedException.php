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

namespace OpenConext\Profile\Exception;

use Assert\AssertionFailedException as AssertAssertionFailedException;

class AssertionFailedException extends InvalidArgumentException implements AssertAssertionFailedException
{
    // @codingStandardsIgnoreStart
    public function __construct($message, $code, private $value, private $propertyPath = null, private readonly array $constraints = [])
    {
        parent::__construct($message, $code);
    }
    // @codingStandardsIgnoreEnd
    /**
     * User controlled way to define a sub-property causing
     * the failure of a currently asserted objects.
     *
     * Useful to transport information about the nature of the error
     * back to higher layers.
     *
     * @return string
     */
    public function getPropertyPath(): ?string
    {
        return $this->propertyPath;
    }

    /**
     * Get the value that caused the assertion to fail.
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
    /**
     * Get the constraints that applied to the failed assertion.
     *
     * @return array
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }
}
