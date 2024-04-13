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

use OpenConext\AttributeAggregationApiClientBundle\Exception\InvalidArgumentException;

/**
 * AttributeAggregationAttributesList as returned from the AA API
 */
final class AttributeAggregationAttributesList
{
    /**
     * @param AttributeAggregationAttribute[] $attributes
     */
    public function __construct(
        private array $attributes,
    ) {
    }

    public static function fromApiResponse(
        array $attributes,
    ): self {
        $attributeCollection = [];
        foreach ($attributes as $attributeData) {
            $attributeCollection[] = AttributeAggregationAttribute::fromApiResponse($attributeData);
        }
        return new self($attributeCollection);
    }

    public function getActiveAttributes(): array
    {
        $output = [];
        foreach ($this->attributes as $attribute) {
            if ($attribute->isConnected()) {
                $output[] = $attribute;
            }
        }
        return $output;
    }

    public function getAvailableAttributes(): array
    {
        $output = [];
        foreach ($this->attributes as $attribute) {
            if (!$attribute->isConnected()) {
                $output[] = $attribute;
            }
        }
        return $output;
    }

    public function hasAttribute(
        string $accountType,
    ): bool {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getAccountType() === $accountType) {
                return true;
            }
        }
        return false;
    }

    public function filterEnabledAttributes(
        AttributeAggregationEnabledAttributes $enabledAttributes,
    ): void {
        $this->attributes = array_filter(
            $this->attributes,
            fn(AttributeAggregationAttribute $attribute): bool
                => $enabledAttributes->isEnabled($attribute->getAccountType()),
        );
    }

    public function getAttribute(
        string $accountType,
    ): AttributeAggregationAttribute {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getAccountType() === $accountType) {
                return $attribute;
            }
        }

        throw new InvalidArgumentException(
            sprintf(
                'The requested attribute for account type "%s" could not be found',
                $accountType,
            ),
        );
    }
}
