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
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $surfconextId;

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
     * @var bool
     */
    private $isConnected = false;

    /**
     * @param int $id
     * @param $surfconextId
     * @param string $accountType
     * @param string $linkedId
     * @param string $logoPath
     * @param string $connectUrl
     * @param bool $isConnected
     */
    public function __construct(
        $id,
        $surfconextId,
        $accountType,
        $linkedId,
        $logoPath,
        $connectUrl,
        $isConnected
    ) {
        $this->id = $id;
        $this->surfconextId = $surfconextId;
        $this->accountType = $accountType;
        $this->linkedId = $linkedId;
        $this->logoPath = $logoPath;
        $this->connectUrl = $connectUrl;
        $this->isConnected = $isConnected;
    }

    public static function fromConfig(
        AttributeAggregationAttributeConfiguration $enabledAttribute,
        $isConnected,
        $id,
        $surfconextId,
        $linkedId = null
    ) {
        return new self(
            $id,
            $surfconextId,
            $enabledAttribute->getAccountType(),
            $linkedId,
            $enabledAttribute->getLogoPath(),
            $enabledAttribute->getConnectUrl(),
            $isConnected
        );
    }

    public static function fromApiResponse(array $attributeData)
    {
        Assertion::keyExists($attributeData, 'id', 'No id found on attribute');
        Assertion::integer($attributeData['id'], 'Id should be integer');

        Assertion::keyExists($attributeData, 'urn', 'No SURFconext Id found on attribute');
        Assertion::string($attributeData['urn'], 'SURFconext Id should be a string');

        Assertion::keyExists($attributeData, 'accountType', 'No account type found on attribute');
        Assertion::string($attributeData['accountType'], 'Account type should be a string');

        Assertion::keyExists($attributeData, 'linkedId', 'No linked id found on attribute');
        Assertion::string($attributeData['linkedId'], 'Linked id should be a string');

        return new self(
            $attributeData['id'],
            $attributeData['urn'],
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
     * @return bool
     */
    public function isConnected()
    {
        return $this->isConnected;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getSurfconextId()
    {
        return $this->surfconextId;
    }
}
