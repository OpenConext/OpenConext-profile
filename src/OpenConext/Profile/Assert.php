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

namespace OpenConext\Profile;

use Assert\Assertion as BaseAssertion;
use OpenConext\Profile\Exception\AssertionFailedException;

class Assert extends BaseAssertion
{
    protected static $exceptionClass = AssertionFailedException::class;

    /**
     * Verifies if the expected keys are in the array
     *
     * An exact match is not required.
     *
     * @param null $propertyPath
     */
    public static function keysArePresent(array $array, array $expectedKeys, $propertyPath = null): void
    {
        $givenKeys = array_keys($array);

        sort($givenKeys);
        sort($expectedKeys);

        if ($givenKeys === $expectedKeys) {
            return;
        }

        foreach ($expectedKeys as $expectedKey) {
            if (!in_array($expectedKey, $givenKeys)) {
                $message = sprintf('Required key "%s" is missing', $expectedKey);
                throw new AssertionFailedException($message, 0, $array, $propertyPath);
            }
        }
        return;
    }
}
