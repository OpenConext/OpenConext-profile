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

namespace OpenConext\ProfileBundle\DependencyInjection;

use DateTime;
use OpenConext\Profile\Value\AttributeAggregation\AttributeAggregationEnabledAttributes;
use OpenConext\Profile\Value\EntityId;
use OpenConext\Profile\Value\Locale;
use OpenConext\Profile\Value\LocaleSet;
use OpenConext\ProfileBundle\Service\UserService;
use OpenConext\ProfileBundle\Storage\SingleCookieStorage;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class OpenConextProfileExtension extends Extension
{
    public function load(
        array $configs,
        ContainerBuilder $container,
    ): void {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $this->parseEngineBlockEntityIdConfiguration($config['engine_block_entity_id'], $container);

        $this->parseAttributeSupportMailConfiguration($config['attribute_support'], $container);
        $this->parseInformationRequestMailConfiguration($config['information_request'], $container);

        $this->parseDefaultLocaleConfiguration($config['default_locale'], $container);
        $this->parseAvailableLocaleConfiguration($config['locales'], $container);
        $this->parseLocaleCookieStorageConfiguration(
            $config['locale_cookie_domain'],
            $config['locale_cookie_key'],
            $config['locale_cookie_expires_in'],
            $config['locale_cookie_secure'],
            $config['locale_cookie_http_only'],
            $container,
        );

        $this->parseEngineBlockAttributeAggregationConfiguration(
            $config['attribute_aggregation_supported_attributes'],
            $container,
        );

        // The user lifecycle can be disabled
        if (!$container->getParameter('user_lifecycle_enabled')) {
            $container->getDefinition(UserService::class)
                ->removeMethodCall('setUserLifecycleApiClient');
        }
    }

    private function parseEngineBlockEntityIdConfiguration(
        $engineBlockEntityId,
        ContainerBuilder $container,
    ): void {
        $container
            ->getDefinition(EntityId::class)
            ->replaceArgument(0, $engineBlockEntityId);
    }

    private function parseAttributeSupportMailConfiguration(
        array $attributeSupportConfig,
        ContainerBuilder $container,
    ): void {
        $container
            ->getDefinition('profile.attribute_support.email_from')
            ->replaceArgument(0, $attributeSupportConfig['email_from']);
        $container
            ->getDefinition('profile.attribute_support.email_to')
            ->replaceArgument(0, $attributeSupportConfig['email_to']);
    }

    private function parseInformationRequestMailConfiguration(
        array $attributeSupportConfig,
        ContainerBuilder $container,
    ): void {
        $container
            ->getDefinition('profile.information_request.email_from')
            ->replaceArgument(0, $attributeSupportConfig['email_from']);
        $container
            ->getDefinition('profile.information_request.email_to')
            ->replaceArgument(0, $attributeSupportConfig['email_to']);
    }

    private function parseDefaultLocaleConfiguration(
        $defaultLocaleConfig,
        ContainerBuilder $container,
    ): void {
        $container
            ->getDefinition(Locale::class)
            ->replaceArgument(0, $defaultLocaleConfig);
    }

    private function parseAvailableLocaleConfiguration(
        array $availableLocaleConfig,
        ContainerBuilder $container,
    ): void {
        $availableLocales = array_map(fn($availableLocale): Definition => new Definition(Locale::class, [$availableLocale]), $availableLocaleConfig);

        $container
            ->getDefinition(LocaleSet::class)
            ->replaceArgument(0, $availableLocales);
    }

    private function parseLocaleCookieStorageConfiguration(
        $localeCookieDomain,
        $localeCookieKey,
        $localeCookieExpiresIn,
        $localeCookieSecure,
        $localeCookieHttpOnly,
        ContainerBuilder $container,
    ): void {

        if ($localeCookieExpiresIn !== null) {
            $localeCookieExpirationDateDefinition = new Definition(DateTime::class);
            $localeCookieExpirationDateDefinition->addMethodCall('modify', [$localeCookieExpiresIn]);
        } else {
            $localeCookieExpirationDateDefinition = null;
        }

        $container
            ->getDefinition(SingleCookieStorage::class)
            ->replaceArgument(0, $localeCookieDomain)
            ->replaceArgument(1, $localeCookieKey)
            ->replaceArgument(2, $localeCookieExpirationDateDefinition)
            ->replaceArgument(3, $localeCookieSecure)
            ->replaceArgument(4, $localeCookieHttpOnly);
    }

    private function parseEngineBlockAttributeAggregationConfiguration(
        $aaConfig,
        ContainerBuilder $container,
    ): void {
        $container
            ->getDefinition(AttributeAggregationEnabledAttributes::class)
            ->replaceArgument(0, $aaConfig);
    }
}
