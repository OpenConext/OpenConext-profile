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

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OpenConext\Profile\Exception\LogicException;
use function ksort;
use const SORT_STRING;

final class SpecifiedConsentList implements IteratorAggregate, Countable
{
    /**
     * @var SpecifiedConsent[]
     */
    private $specifiedConsents = [];

    /**
     * @param SpecifiedConsent[] $specifiedConsents
     * @return SpecifiedConsentList
     */
    public static function createWith(array $specifiedConsents)
    {
        return new self($specifiedConsents);
    }

    /**
     * @param SpecifiedConsent[] $specifiedConsents
     */
    private function __construct(array $specifiedConsents)
    {
        foreach ($specifiedConsents as $specifiedConsent) {
            $this->initializeWith($specifiedConsent);
        }
    }

    public function sortByDisplayName(string $locale): void
    {
        $sorted = [];
        $sortedByEntityId = [];
        /** @var SpecifiedConsent $consent */
        foreach ($this->getIterator() as $consent) {
            $displayName = $consent->getServiceProvider()->getLocaleAwareEntityName($locale);
            if ($consent->getServiceProvider()->getDisplayName()->hasFilledTranslationForLocale($locale)) {
                $sorted[$displayName] = $consent;
            } else {
                $sortedByEntityId[$displayName] = $consent;
            }
        }
        ksort($sorted, SORT_STRING);
        ksort($sortedByEntityId, SORT_STRING);
        $this->specifiedConsents = $sorted +  $sortedByEntityId;
    }
    /**
     * @param SpecifiedConsent $specifiedConsent
     */
    private function initializeWith(SpecifiedConsent $specifiedConsent)
    {
        $this->specifiedConsents[] = $specifiedConsent;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->specifiedConsents);
    }

    public function count()
    {
        return count($this->specifiedConsents);
    }
}
