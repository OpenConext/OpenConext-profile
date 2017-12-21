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
    private $accountType;

    /**
     * @var string
     */
    private $linkedId;

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
     * @param string $accountType
     * @param string $linkedId
     * @param string $logoPath
     * @param string $connectUrl
     * @param string $disconnectUrl
     * @param bool $isConnected
     */
    public function __construct(
        $accountType,
        $linkedId,
        $logoPath,
        $connectUrl,
        $disconnectUrl,
        $isConnected
    ) {
        $this->accountType = $accountType;
        $this->linkedId = $linkedId;
        $this->logoPath = $logoPath;
        $this->connectUrl = $connectUrl;
        $this->disconnectUrl = $disconnectUrl;
        $this->isConnected = $isConnected;
    }

    public static function fromConfig(
        AttributeAggregationAttributeConfiguration $enabledAttribute,
        $isConnected,
        $linkedId = null
    ) {
        return new self(
            $enabledAttribute->getAccountType(),
            $linkedId,
            $enabledAttribute->getLogoPath(),
            $enabledAttribute->getConnectUrl(),
            $enabledAttribute->getDisconnectUrl(),
            $isConnected
        );
    }

    public static function fromApiResponse(array $attributeData)
    {
        Assertion::keyExists($attributeData, 'accountType', 'No account type found on attribute');
        Assertion::string($attributeData['accountType'], 'Account type should be a string');

        Assertion::keyExists($attributeData, 'linkedId', 'No linked id found on attribute');
        Assertion::string($attributeData['linkedId'], 'Linked id should be a string');

        return new self(
            $attributeData['accountType'],
            $attributeData['linkedId'],
            '',
            '',
            '',
            true
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
    public function getLinkedId()
    {
        return $this->linkedId;
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
