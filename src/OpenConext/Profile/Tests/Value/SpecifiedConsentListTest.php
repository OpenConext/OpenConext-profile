<?php

declare(strict_types = 1);

/**
 * Copyright 2021 SURFnet B.V.
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

namespace OpenConext\Profile\Tests\Value;

use OpenConext\Profile\Value\Consent\ServiceProvider;
use OpenConext\Profile\Value\DisplayName;
use OpenConext\Profile\Value\SpecifiedConsent;
use OpenConext\Profile\Value\SpecifiedConsentList;
use PHPUnit\Framework\TestCase;
use function array_shift;

class SpecifiedConsentListTest extends TestCase
{
    public function test_it_can_order_by_display_name_of_sp(): void
    {
        $locale = 'en';
        $specifiedConsent = [
            $this->buildMockSpecifiedConsent($locale, 'C-service'),
            $this->buildMockSpecifiedConsent($locale, 'A-service'),
            $this->buildMockSpecifiedConsent($locale, 'B-service'),
        ];
        $list = SpecifiedConsentList::createWith($specifiedConsent);
        $list->sortByDisplayName('en');
        $sorted = $list->getIterator()->getArrayCopy();
        $this->assertEquals('A-service', array_shift($sorted)->getServiceProvider()->getLocaleAwareEntityName($locale));
        $this->assertEquals('B-service', array_shift($sorted)->getServiceProvider()->getLocaleAwareEntityName($locale));
        $this->assertEquals('C-service', array_shift($sorted)->getServiceProvider()->getLocaleAwareEntityName($locale));
    }

    /**
     * The entities with a display name are sorted on top of the list, followed by the ones with only an entityID.
     * Both lists are sorted alphabetically.
     */
    public function test_it_can_order_by_display_name_of_sp_handle_sps_without_display_name_correctly(): void
    {
        $locale = 'en';
        $specifiedConsent = [
            $this->buildMockSpecifiedConsent($locale, '', 'https://aa.example.com/metadata'),
            $this->buildMockSpecifiedConsent($locale, 'https://selfservice'),
            $this->buildMockSpecifiedConsent($locale, '', 'https://selfservice.stepup.example.com/metadata'),
            $this->buildMockSpecifiedConsent($locale, 'Healty-service'),
        ];
        $list = SpecifiedConsentList::createWith($specifiedConsent);
        $list->sortByDisplayName('en');
        /** @var SpecifiedConsent[] $sorted */
        $sorted = $list->getIterator()->getArrayCopy();
        $this->assertEquals('Healty-service', array_shift($sorted)->getServiceProvider()->getLocaleAwareEntityName($locale));
        $this->assertEquals('https://selfservice', array_shift($sorted)->getServiceProvider()->getLocaleAwareEntityName($locale));
        $this->assertEquals('https://aa.example.com/metadata', array_shift($sorted)->getServiceProvider()->getLocaleAwareEntityName($locale));
        $this->assertEquals('https://selfservice.stepup.example.com/metadata', array_shift($sorted)->getServiceProvider()->getLocaleAwareEntityName($locale));
    }

    public function test_it_can_order_nothing(): void
    {
        $specifiedConsent = [];
        $list = SpecifiedConsentList::createWith($specifiedConsent);
        $list->sortByDisplayName('nl');
        $this->assertEmpty($list);
    }

    private function buildMockSpecifiedConsent(string $locale, string $displayName, string $entityId = '')
    {
        $mockSp = $this->createMock(ServiceProvider::class);
        if ($entityId === '') {
            $mockSp->method('getLocaleAwareEntityName')->with($locale)->willReturn($displayName);

            $displayNameMock = $this->createMock(DisplayName::class);
            $displayNameMock->method('hasFilledTranslationForLocale')->with($locale)->willReturn(true);

            $mockSp->method('getDisplayName')->willReturn($displayNameMock);
        } else {
            $mockSp->method('getLocaleAwareEntityName')->with($locale)->willReturn($entityId);

            $displayNameMock = $this->createMock(DisplayName::class);
            $displayNameMock->method('hasFilledTranslationForLocale')->with($locale)->willReturn(false);

            $mockSp->method('getDisplayName')->willReturn($displayNameMock);
        }

        $mock = $this->createMock(SpecifiedConsent::class);
        $mock->method('getServiceProvider')->willReturn($mockSp);

        return $mock;
    }
}
