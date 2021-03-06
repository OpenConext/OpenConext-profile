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

namespace OpenConext\Profile\Value\AttributeAggregation;

final class AttributeAggregationAttributeConfiguration
{
    /**
     * @var string
     */
    private $accountType;

    /**
     * @var string
     */
    private $logoPath;

    /**
     * @var string
     */
    private $connectUrl;

    /**
     * @param string $accountType
     * @param string $logoPath
     * @param string $connectUrl
     */
    public function __construct($accountType, $logoPath, $connectUrl)
    {
        $this->accountType = $accountType;
        $this->logoPath = $logoPath;
        $this->connectUrl = $connectUrl;
    }

    public static function fromConfig($accountType, $attributeConfigParameters)
    {
        return new self(
            $accountType,
            $attributeConfigParameters['logo_path'],
            $attributeConfigParameters['connect_url']
        );
    }

    /**
     * @return string
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * @return string
     */
    public function getLogoPath()
    {
        return $this->logoPath;
    }

    /**
     * @return string
     */
    public function getConnectUrl()
    {
        return $this->connectUrl;
    }
}
