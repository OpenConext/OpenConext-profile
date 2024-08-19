<?php

/**
 * Copyright 2024 SURFnet B.V.
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

declare(strict_types=1);

namespace Tests\OpenConext\InviteApiClientBundle\Value;

use InvalidArgumentException;
use OpenConext\InviteApiClientBundle\Value\InviteRoleListFactory;
use OpenConext\Profile\Value\InviteRole;
use OpenConext\Profile\Value\InviteRoleList;
use PHPUnit\Framework\TestCase;
use TypeError;

class InviteRoleListFactoryTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testCreateList(string $jsonData, int $expectedCount): void
    {
        $data = json_decode($jsonData, true);

        $result = InviteRoleListFactory::createList($data);

        $this->assertInstanceOf(InviteRoleList::class, $result);
        $this->assertCount($expectedCount, $result);

        $roles = $result->getIterator()->getArrayCopy();
        $this->assertContainsOnlyInstancesOf(InviteRole::class, $roles);

        foreach ($roles as $index => $role) {
            $this->assertEquals($data[$index]['name'], $role->getName());
            $this->assertEquals($data[$index]['description'], $role->getDescription());
        }
    }

    public function validDataProvider(): array
    {
        return [
            'no api response' => ['[]', 0],
            'response with two roles' => [
                '[
                    {
                        "name": "Admin Role",
                        "description": "Administrator role with full access",
                        "applications": [
                            {
                                "landingPage": "https://admin.example.com",
                                "nameEn": "Admin Panel",
                                "nameNl": "Beheerderspaneel",
                                "organisationEn": "Example Org",
                                "organisationNl": "Voorbeeldorganisatie",
                                "logo": "https://example.com/logo.png"
                            }
                        ]
                    },
                    {
                        "name": "User Role",
                        "description": "Standard user role",
                        "applications": [
                            {
                                "landingPage": "https://user.example.com",
                                "nameEn": "User Dashboard",
                                "nameNl": "Gebruikersdashboard",
                                "organisationEn": "Example Org",
                                "organisationNl": "Voorbeeldorganisatie",
                                "logo": "https://example.com/logo.png"
                            }
                        ]
                    }
                ]',
                2
            ],
            'without applications section' => [
                '[
                    {
                        "name": "Empty App Role",
                        "description": "Role with no applications",
                        "applications": []
                    }
                ]',
                1
            ],
            'missing applications' => [
                '[
                    {
                        "name": "No App Role",
                        "description": "Role with missing applications key"
                    }
                ]',
                1
            ],
            'empty strings' => [
                '[
                    {
                        "name": "",
                        "description": "",
                        "applications": []
                    }
                ]',
                1
            ],
            'special characters' => [
                '[
                    {
                        "name": "Special ÇhåráctÐrs !@#$%^&*()",
                        "description": "Dèscríptîøn with špeçïal çhäræctêrs",
                        "applications": []
                    }
                ]',
                1
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testCreateListWithInvalidData(string $jsonData, string $expectedException): void
    {
        $this->expectException($expectedException);

        $data = json_decode($jsonData, true);
        InviteRoleListFactory::createList($data);
    }

    public function invalidDataProvider(): array
    {
        return [
            'empty array' => ['[]', InvalidArgumentException::class],
            'missing name' => [
                '[{"description": "Missing name"}]',
                TypeError::class
            ],
            'missing description' => [
                '[{"name": "Missing Description"}]',
                TypeError::class
            ],
            'invalid types' => [
                '[{"name": 123, "description": 456}]',
                TypeError::class
            ],
            'null values' => [
                '[{"name": null, "description": null}]',
                TypeError::class
            ]
        ];
    }
}
