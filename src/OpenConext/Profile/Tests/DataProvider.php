<?php

declare(strict_types=1);

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

trait DataProvider
{
    public function notObjectProvider()
    {
        return [
            'string' => [''],
            'int'    => [1],
            'float'  => [1.23],
            'null'   => [null],
            'bool'   => [false],
            'array'  => [[]]
        ];
    }

    public function notNonEmptyOrBlankStringProvider()
    {
        return [
            'empty string' => [''],
            'blank string' => ['   '],
        ];
    }

    public function nonStringProvider()
    {
        return [
            'int'      => [1],
            'int 0'    => [0],
            'float'    => [1.23],
            'float 0'  => [0.0],
            'null'     => [null],
            'bool'     => [false],
            'array'    => [[]],
            'object'   => [new \stdClass],
            'resource' => [fopen('php://memory', 'w')],
        ];
    }

    public function intFloatBoolProvider()
    {
        return [
            'int 0'   => [0],
            'int'     => [1],
            'float 0' => [0.0],
            'float'   => [1.23],
            'bool'    => [false],
        ];
    }
}
