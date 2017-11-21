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

namespace OpenConext\Profile\Tests;

use OpenConext\Profile\Assert;
use OpenConext\Profile\Exception\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class AssertTest extends TestCase
{
    /**
     * @test
     * @group Assert
     * @dataProvider missingKeysProvider
     *
     * @expectedException \OpenConext\Profile\Exception\AssertionFailedException
     * @expectedExceptionMessage Required keys
     */
    public function missing_required_keys_trigger_an_exception($givenArray, $expectedKeys)
    {
        Assert::keysAre($givenArray, $expectedKeys);
    }

    public function missingKeysProvider()
    {
        return [
            'empty given array' => [[], ['MissingOne', 'MissingTwo']],
            'single missing key' => [['A' => 'a'], ['A', 'Missing']],
            'multiple missing keys' => [['A' => 'a'], ['A', 'MissingOne', 'MissingTwo']],
        ];
    }

    /**
     * @test
     * @group Assert
     * @dataProvider additionalKeysProvider
     *
     * @expectedException \OpenConext\Profile\Exception\AssertionFailedException
     * @expectedExceptionMessage Additional keys
     */
    public function additional_keys_trigger_an_exception($givenArray, $expectedKeys)
    {
        Assert::keysAre($givenArray, $expectedKeys);
    }

    public function additionalKeysProvider()
    {
        return [
            'empty expected keys' => [['AdditionalOne' => 'a', 'AdditionalTwo'=> 'b'], []],
            'single additional key' => [['A' => 'a', 'Additional' => 'b'], ['A']],
            'multiple additional keys' => [['A' => 'a', 'AdditionalOne' => 'b', 'AdditionalTwo' => 'c'], ['A']],
        ];
    }

    /**
     * @test
     * @group Assert
     * @dataProvider notMatchingKeysProvider
     *
     * @expectedException \OpenConext\Profile\Exception\AssertionFailedException
     * @expectedExceptionMessage Keys do not match requirements
     */
    public function not_matching_keys_trigger_an_exception($givenArray, $expectedKeys)
    {
        Assert::keysAre($givenArray, $expectedKeys);
    }

    public function notMatchingKeysProvider()
    {
        return [
            'one key differs' => [['A' => 'a', 'B' => 'b'], ['A', 'C']],
            'two keys differ' => [['A' => 'a', 'B' => 'b'], ['C', 'D']]
        ];
    }

    /**
     * @test
     * @group Assert
     * @dataProvider matchingKeysProvider
     */
    public function matching_keys_do_not_trigger_an_exception($givenArray, $expectedKeys)
    {
        $exceptionIsThrown = false;

        try {
            Assert::keysAre($givenArray, $expectedKeys);
        } catch (AssertionFailedException $e) {
            $exceptionIsThrown = true;
        }

        $this->assertFalse($exceptionIsThrown);
    }

    public function matchingKeysProvider()
    {
        return [
            'empty array and no keys' => [[], []],
            'single key present in array' => [['A' => 'a'], ['A']],
            'multiple keys present in array' => [['A' => 'a', 'B' => 'b'], ['A', 'B']],
        ];
    }
}
