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

use Assert\Assertion;

final class AttributeAggregationAttribute
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $values;

    /**
     * @var string
     */
    private $source;

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
     * @param array $values
     * @param $source
     */
    public function __construct(
        $identifier,
        $logoPath,
        $connectUrl,
        $disconnectUrl,
        $isConnected,
        array $values = null,
        $source = null
    ) {
        $this->identifier = $identifier;
        $this->logoPath = $logoPath;
        $this->connectUrl = $connectUrl;
        $this->disconnectUrl = $disconnectUrl;
        $this->isConnected = $isConnected;
        $this->values = $values;
        $this->source = $source;
    }

    public static function fromConfig(
        AttributeAggregationAttributeConfiguration $enabledAttribute,
        $isConnected,
        array $values = null,
        $source = null
    ) {
        return new self(
            $enabledAttribute->getIdentifier(),
            $enabledAttribute->getLogoPath(),
            $enabledAttribute->getConnectUrl(),
            $enabledAttribute->getDisconnectUrl(),
            $isConnected,
            $values,
            $source
        );
    }

    public static function fromApiResponse(array $attributeData)
    {
        Assertion::keyExists($attributeData, 'name', 'The name should be set on the attribute');
        Assertion::string($attributeData['name'], 'The name should be a string');
        Assertion::keyExists($attributeData, 'values', 'The values should be set on the attribute');
        Assertion::isArray($attributeData['values'], 'The values should be an array');
        Assertion::keyExists($attributeData, 'source', 'The source should be set on the attribute');
        Assertion::string($attributeData['source'], 'The source should be a string');

        $attribute = new self(
            $attributeData['name'],
            '',
            '',
            '',
            true,
            $attributeData['values'],
            $attributeData['source']
        );
        return $attribute;
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

    /**
     * @return string
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }
}
