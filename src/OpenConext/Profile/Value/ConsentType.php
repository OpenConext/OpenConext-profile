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

namespace OpenConext\Profile\Value;

use OpenConext\Profile\Assert;

final class ConsentType
{
    const TYPE_EXPLICIT = 'explicit';
    const TYPE_IMPLICIT = 'implicit';

    /**
     * @var ConsentType
     */
    private $consentType;

    /**
     * @return ConsentType
     */
    public static function explicit()
    {
        return new self(self::TYPE_EXPLICIT);
    }

    /**
     * @return ConsentType
     */
    public static function implicit()
    {
        return new self(self::TYPE_IMPLICIT);
    }

    /**
     * @param string $consentType
     */
    private function __construct($consentType)
    {
        Assert::choice(
            $consentType,
            [ConsentType::TYPE_EXPLICIT, ConsentType::TYPE_IMPLICIT],
            '"%s" is not one of the valid ConsentTypes: %s'
        );

        $this->consentType = $consentType;
    }

    /**
     * @param ConsentType $other
     * @return bool
     */
    public function equals(ConsentType $other)
    {
        return $this->consentType === $other->consentType;
    }

    /**
     * @return bool
     */
    public function isExplicit()
    {
        return $this->consentType === self::TYPE_EXPLICIT;
    }

    /**
     * @return bool
     */
    public function isImplicit()
    {
        return $this->consentType === self::TYPE_IMPLICIT;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->consentType;
    }
}
