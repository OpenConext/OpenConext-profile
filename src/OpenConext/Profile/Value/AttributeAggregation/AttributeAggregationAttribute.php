<?php

declare(strict_types = 1);

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

final readonly class AttributeAggregationAttribute
{
    public function __construct(
        private int    $id,
        private string $userId,
        private string $accountType,
        private ?string $linkedId,
        private string $logoPath,
        private string $connectUrl,
        private bool   $isConnected,
    ) {
    }

    public static function fromConfig(
        AttributeAggregationAttributeConfiguration $enabledAttribute,
        $isConnected,
        $id,
        $userId,
        $linkedId = null,
    ): AttributeAggregationAttribute {
        return new self(
            $id,
            $userId,
            $enabledAttribute->getAccountType(),
            $linkedId,
            $enabledAttribute->getLogoPath(),
            $enabledAttribute->getConnectUrl(),
            $isConnected,
        );
    }

    public static function fromApiResponse(
        array $attributeData,
    ): AttributeAggregationAttribute {
        Assertion::keyExists($attributeData, 'id', 'No id found on attribute');
        Assertion::integer($attributeData['id'], 'Id should be integer');

        Assertion::keyExists($attributeData, 'urn', 'No userId name id found on attribute');
        Assertion::string($attributeData['urn'], 'userId name id Id should be a string');

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
            true,
        );
    }

    public function getAccountType(): string
    {
        return $this->accountType;
    }

    public function getLinkedId(): string
    {
        return $this->linkedId;
    }

    public function getLogoPath(): string
    {
        return $this->logoPath;
    }

    public function getConnectUrl(): string
    {
        return $this->connectUrl;
    }

    public function isConnected(): bool
    {
        return $this->isConnected;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserNameId(): string
    {
        return $this->userId;
    }
}
