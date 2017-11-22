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

final class AttributeAggregationAttribute
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $logoPath;

    /**
     * @var string
     */
    private $connectUrl;

    /**
     * @var string
     */
    private $disconnectUrl;

    /**
     * @var bool
     */
    private $isConnected = false;

    /**
     * @param string $identifier
     * @param string $logoPath
     * @param string $connectUrl
     * @param string $disconnectUrl
     * @param bool $isConnected
     */
    public function __construct($identifier, $logoPath, $connectUrl, $disconnectUrl, $isConnected)
    {
        $this->identifier = $identifier;
        $this->logoPath = $logoPath;
        $this->connectUrl = $connectUrl;
        $this->disconnectUrl = $disconnectUrl;
        $this->isConnected = $isConnected;
    }

    public static function fromConfig(AttributeAggregationAttributeConfiguration $enabledAttribute, $isConnected)
    {
        return new self(
            $enabledAttribute->getIdentifier(),
            $enabledAttribute->getLogoPath(),
            $enabledAttribute->getConnectUrl(),
            $enabledAttribute->getDisconnectUrl(),
            $isConnected
        );
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
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

    /**
     * @return string
     */
    public function getDisconnectUrl()
    {
        return $this->disconnectUrl;
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        return $this->isConnected;
    }
}
