<?php

declare(strict_types = 1);

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

namespace OpenConext\ProfileBundle\Service;

use OpenConext\Profile\Value\ContactPerson;
use OpenConext\Profile\Value\ContactType;
use OpenConext\Profile\Repository\ContactPersonRepositoryInterface;
use OpenConext\Profile\Value\EntityId;

final readonly class SupportContactEmailService
{
    public function __construct(private ContactPersonRepositoryInterface $contactPersonRepository)
    {
    }

    /**
     * @param string EntityId $entityId
     * @return null|ContactEmailAddress
     */
    public function findSupportContactEmailForIdp(EntityId $entityId)
    {
        $supportContactPersons = $this->contactPersonRepository->findAllForIdp($entityId)->filter(
            fn(ContactPerson $contactPerson): bool => $contactPerson->hasContactTypeOf(new ContactType(ContactType::TYPE_SUPPORT))
                && $contactPerson->hasEmailAddress(),
        );

        if (count($supportContactPersons) === 0) {
            return null;
        }

        return $supportContactPersons->first()->getEmailAddress();
    }
}
