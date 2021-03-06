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

use OpenConext\AttributeAggregationApiClientBundle\Exception\InvalidArgumentException;

/**
 * AttributeAggregationAttributesList as returned from the AA API
 */
final class AttributeAggregationAttributesList
{
    /**
     * @var AttributeAggregationAttribute[]
     */
    private $attributes;

    /**
     * @param AttributeAggregationAttribute[] $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @param array $attributes
     * @return AttributeAggregationAttributesList
     */
    public static function fromApiResponse(array $attributes)
    {
        $attributeCollection = [];
        foreach ($attributes as $attributeData) {
            $attributeCollection[] = AttributeAggregationAttribute::fromApiResponse($attributeData);
        }
        return new self($attributeCollection);
    }

    public function getActiveAttributes()
    {
        $output = [];
        foreach ($this->attributes as $attribute) {
            if ($attribute->isConnected()) {
                $output[] = $attribute;
            }
        }
        return $output;
    }

    public function getAvailableAttributes()
    {
        $output = [];
        foreach ($this->attributes as $attribute) {
            if (!$attribute->isConnected()) {
                $output[] = $attribute;
            }
        }
        return $output;
    }

    /**
     * @param string $accountType
     * @return bool
     */
    public function hasAttribute($accountType)
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getAccountType() === $accountType) {
                return true;
            }
        }
        return false;
    }

    public function filterEnabledAttributes(AttributeAggregationEnabledAttributes $enabledAttributes)
    {
        $this->attributes = array_filter(
            $this->attributes,
            function (AttributeAggregationAttribute $attribute) use ($enabledAttributes) {
                return $enabledAttributes->isEnabled($attribute->getAccountType());
            }
        );
    }

    /**
     * @param string $accountType
     * @return AttributeAggregationAttribute
     * @throws InvalidArgumentException
     */
    public function getAttribute($accountType)
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getAccountType() === $accountType) {
                return $attribute;
            }
        }

        throw new InvalidArgumentException(
            sprintf(
                'The requested attribute for account type "%s" could not be found',
                $accountType
            )
        );
    }
}
