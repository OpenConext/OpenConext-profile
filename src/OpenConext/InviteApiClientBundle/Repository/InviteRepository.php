<?php

declare(strict_types = 1);

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

namespace OpenConext\InviteApiClientBundle\Repository;

use OpenConext\InviteApiClientBundle\Http\JsonApiClient;
use OpenConext\InviteApiClientBundle\Value\InviteRoleListFactory;
use OpenConext\Profile\Repository\InviteRepositoryInterface;
use OpenConext\Profile\Value\InviteRoleList;
use Psr\Log\LoggerInterface;
use function json_decode;

final readonly class InviteRepository implements InviteRepositoryInterface
{
    public function __construct(
        private JsonApiClient $apiClient,
        private LoggerInterface $logger,
    ) {
    }

    public function findAllFor(
        string $collabPersonId,
    ): InviteRoleList {
        $this->logger->info(sprintf('OpenConext-invite API: GET v1/profile for "%s', $collabPersonId));
        $inviteRoleList = $this->apiClient->read('v1/profile', ['collabPersonId' => $collabPersonId]);
        $inviteRoleList = json_decode('[
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
                                "logo": "https://marketplace.canva.com/EAFQvjYQ5x4/1/0/1600w/canva-brown-cream-aesthetic-bakso-logo-L6bA2WdwREE.jpg"
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
                                "logo": "https://marketplace.canva.com/EAFQvjYQ5x4/1/0/1600w/canva-brown-cream-aesthetic-bakso-logo-L6bA2WdwREE.jpg"
                            }
                        ]
                    },
                    {
                        "name": "The third one",
                        "description": "Lorem ipsum dolor sit",
                        "applications": []
                    }
                ]', true);
        return InviteRoleListFactory::createList($inviteRoleList);
    }
}
