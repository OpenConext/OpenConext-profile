<?php

/**
 * Copyright 2017 SURFnet B.V.
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

namespace OpenConext\Profile\Value;

use OpenConext\Profile\Assert;

final class SurfConextId
{
    /**
     * @var string
     */
    private $surfConextId;

    /**
     * @param string $surfConextId
     */
    public function __construct($surfConextId)
    {
        Assert::notEmpty($surfConextId);
        Assert::string($surfConextId);
        Assert::notBlank($surfConextId);

        $this->surfConextId = $surfConextId;
    }

    /**
     * @return string
     */
    public function getSurfConextId()
    {
        return $this->surfConextId;
    }

    public function __toString()
    {
        return $this->surfConextId;
    }
}
