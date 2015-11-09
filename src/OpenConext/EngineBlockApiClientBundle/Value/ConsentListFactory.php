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
use OpenConext\Profile\Value\DisplayName;
use OpenConext\Profile\Value\EmailAddress;
use OpenConext\Profile\Value\Entity;
use OpenConext\Profile\Value\EntityId;
use OpenConext\Profile\Value\EntityType;
use OpenConext\Profile\Value\Url;

final class ConsentListFactory
{
    /**
     * @param mixed $struct
     * @return ConsentList
     */
    public static function create($struct)
    {
        Assert::isArray($struct, 'Consent list JSON structure must be an associative array, got %s');

        return new ConsentList(array_map([self::class, 'createConsent'], $struct));
    }

    /**
     * @param mixed $struct
     * @return Consent
     *
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     */
    private static function createConsent($struct)
    {
        Assert::keyExists($struct, 'service_provider', 'Consent JSON structure must contain key "service_provider"');
        Assert::keyExists($struct, 'consent_given_on', 'Consent JSON structure must contain key "consent_given_on"');
        Assert::keyExists($struct, 'last_used_on', 'Consent JSON structure must contain key "last_used_on"');

        $consentGivenOn = DateTimeImmutable::createFromFormat(DateTime::ATOM, $struct['consent_given_on']);
        $lastUsedOn = DateTimeImmutable::createFromFormat(DateTime::ATOM, $struct['last_used_on']);

        Assert::isInstanceOf(
            $consentGivenOn,
            DateTimeImmutable::class,
            sprintf(
                'Consent given on date must be formatted according to the ISO8601 standard, got "%s"',
                $struct['consent_given_on']
            )
        );
        Assert::isInstanceOf(
            $lastUsedOn,
            DateTimeImmutable::class,
            sprintf(
                'Last used on date must be formatted according to the ISO8601 standard, got "%s"',
                $struct['last_used_on']
            )
        );

        return new Consent(
            self::createServiceProvider($struct['service_provider']),
            $consentGivenOn,
            $lastUsedOn
        );
    }

    /**
     * @param mixed $struct
     * @return ServiceProvider
     */
    private static function createServiceProvider($struct)
    {
        Assert::keyExists($struct, 'entity_id', 'Consent JSON structure must contain key "entity_id"');
        Assert::keyExists($struct, 'display_name', 'Consent JSON structure must contain key "display_name"');
        Assert::keyExists($struct, 'eula_url', 'Consent JSON structure must contain key "eula_url"');
        Assert::keyExists($struct, 'support_email', 'Consent JSON structure must contain key "support_email"');

        $entity       = new Entity(new EntityId($struct['entity_id']), EntityType::SP());
        $displayName  = new DisplayName($struct['display_name']);
        $eulaUrl      = null;
        $supportEmail = null;

        if ($struct['eula_url'] !== null) {
            $eulaUrl = new Url($struct['eula_url']);
        }

        if ($struct['support_email'] !== null) {
            $supportEmail = new EmailAddress($struct['support_email']);
        }

        return new ServiceProvider($entity, $displayName, $eulaUrl, $supportEmail);
    }
}
