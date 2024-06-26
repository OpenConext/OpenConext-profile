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

namespace OpenConext\EngineBlockApiClient\Value;

use Assert\AssertionFailedException;
use OpenConext\Profile\Assert;
use OpenConext\Profile\Value\ContactEmailAddress;
use OpenConext\Profile\Value\ContactPerson;
use OpenConext\Profile\Value\ContactPersonList;
use OpenConext\Profile\Value\ContactType;

final class ContactPersonListFactory
{
    /**
     * @throws AssertionFailedException
     */
    public static function createListFromMetadata(
        mixed $data,
    ): ContactPersonList {
        Assert::isArray($data, 'Metadata JSON structure must be an associative array, got %s');
        Assert::keyExists($data, 'contact_persons', 'Entity JSON structure must contain key "contact_persons"');
        Assert::isArray($data['contact_persons'], 'Contact persons JSON structure must be an associative array, got %s');

        // We cannot use self::class because translation extractions fails on that
        $contactPersons = array_map(
            ContactPersonListFactory::createContactPerson(...),
            $data['contact_persons'],
        );

        return new ContactPersonList($contactPersons);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     * @throws AssertionFailedException
     */
    private static function createContactPerson(
        mixed $data,
    ): ContactPerson {
        Assert::isArray($data, 'Contact person JSON structure must be an associative array, got %s');
        Assert::keyExists($data, 'contact_type', 'Contact person JSON structure must contain key "contact_type"');

        if (!array_key_exists('email_address', $data)) {
            return new ContactPerson(new ContactType($data['contact_type']));
        }

        return new ContactPerson(
            new ContactType($data['contact_type']),
            new ContactEmailAddress($data['email_address']),
        );
    }
}
