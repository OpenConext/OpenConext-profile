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

namespace OpenConext\EngineBlockApiClientBundle\Value;

use DateTime;
use DateTimeImmutable;
use OpenConext\Profile\Assert;
use OpenConext\Profile\Value\Consent;
use OpenConext\Profile\Value\Consent\ServiceProvider;
use OpenConext\Profile\Value\ConsentList;
use OpenConext\Profile\Value\ConsentType;
use OpenConext\Profile\Value\DisplayName;
use OpenConext\Profile\Value\EmailAddress;
use OpenConext\Profile\Value\Entity;
use OpenConext\Profile\Value\EntityId;
use OpenConext\Profile\Value\EntityType;
use OpenConext\Profile\Value\Url;

final class ConsentListFactory
{
    /**
     * @param mixed $data
     * @return ConsentList
     */
    public static function create($data)
    {
        Assert::isArray($data, 'Consent list JSON structure must be an associative array, got %s');

        return new ConsentList(array_map([self::class, 'createConsent'], $data));
    }

    /**
     * @param mixed $data
     * @return Consent
     *
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     */
    private static function createConsent($data)
    {
        Assert::keyExists($data, 'service_provider', 'Consent JSON structure must contain key "service_provider"');
        Assert::keyExists($data, 'consent_given_on', 'Consent JSON structure must contain key "consent_given_on"');
        Assert::keyExists($data, 'last_used_on', 'Consent JSON structure must contain key "last_used_on"');
        Assert::keyExists($data, 'consent_type', 'Consent JSON structure must contain key "consent_type"');

        Assert::choice(
            $data['consent_type'],
            [ConsentType::TYPE_EXPLICIT, ConsentType::TYPE_IMPLICIT],
            '"%s" is not one of the valid ConsentTypes: %s'
        );

        $consentGivenOn = DateTimeImmutable::createFromFormat(DateTime::ATOM, $data['consent_given_on']);
        $lastUsedOn = DateTimeImmutable::createFromFormat(DateTime::ATOM, $data['last_used_on']);

        Assert::isInstanceOf(
            $consentGivenOn,
            DateTimeImmutable::class,
            sprintf(
                'Consent given on date must be formatted according to the ISO8601 standard, got "%s"',
                $data['consent_given_on']
            )
        );
        Assert::isInstanceOf(
            $lastUsedOn,
            DateTimeImmutable::class,
            sprintf(
                'Last used on date must be formatted according to the ISO8601 standard, got "%s"',
                $data['last_used_on']
            )
        );

        if ($data['consent_type'] === ConsentType::TYPE_EXPLICIT) {
            $consentType = ConsentType::explicit();
        } else if ($data['consent_type'] === ConsentType::TYPE_IMPLICIT) {
            $consentType = ConsentType::implicit();
        }

        return new Consent(
            self::createServiceProvider($data['service_provider']),
            $consentGivenOn,
            $lastUsedOn,
            $consentType
        );
    }

    /**
     * @param mixed $data
     * @return ServiceProvider
     */
    private static function createServiceProvider($data)
    {
        Assert::keyExists($data, 'entity_id', 'Consent JSON structure must contain key "entity_id"');
        Assert::keyExists($data, 'display_name', 'Consent JSON structure must contain key "display_name"');
        Assert::keyExists($data, 'eula_url', 'Consent JSON structure must contain key "eula_url"');
        Assert::keyExists($data, 'support_email', 'Consent JSON structure must contain key "support_email"');

        $entity       = new Entity(new EntityId($data['entity_id']), EntityType::SP());
        $displayName  = new DisplayName($data['display_name']);
        $eulaUrl      = null;
        $supportEmail = null;

        if ($data['eula_url'] !== null) {
            $eulaUrl = new Url($data['eula_url']);
        }

        if ($data['support_email'] !== null) {
            $supportEmail = new EmailAddress($data['support_email']);
        }

        return new ServiceProvider($entity, $displayName, $eulaUrl, $supportEmail);
    }
}
