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

use DateTimeImmutable;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('open_conext_profile');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('engine_block_entity_id')
                    ->info('The EntityID of EngineBlock')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(fn($entityId): bool => !is_string($entityId))
                        ->thenInvalid('EngineBlock EntityID should be a string')
                    ->end()
                ->end()
                ->booleanNode('remove_consent_enabled')
                    ->info('This is the feature flag that toggles the remove consent feature')
                    ->isRequired()
                ->end()
                ->booleanNode('invite_roles_enabled')
                    ->info('This is the feature flag that toggles the OpenConext Invite roles page feature')
                    ->isRequired()
                ->end()
            ->end();

        $this->setupLocaleConfiguration($rootNode);
        $this->setupAttributeSupportConfiguration($rootNode);
        $this->setupAttributeAggregationAttributeConfiguration($rootNode);
        $this->setupInformationRequestConfiguration($rootNode);

        return $treeBuilder;
    }

    private function setupLocaleConfiguration(
        ArrayNodeDefinition $rootNode,
    ): void {
        $rootNode
            ->children()
                ->arrayNode('locales')
                    ->info('The available application locales')
                    ->isRequired()
                    ->prototype('scalar')
                        ->validate()
                            ->ifTrue(fn($locale): bool => !is_string($locale))
                            ->thenInvalid('Available application locales should be strings')
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('default_locale')
                    ->info('The default application locale')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(fn($locale): bool => !is_string($locale))
                        ->thenInvalid('Default application locale should be a string')
                    ->end()
                ->end()
                ->scalarNode('locale_cookie_domain')
                    ->info('The domain for which the locale cookie is set')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(fn($domain): bool => !is_string($domain))
                        ->thenInvalid('Locale cookie domain should be a string')
                    ->end()
                ->end()
                ->scalarNode('locale_cookie_key')
                    ->info('The key for which the locale cookie value is set')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(fn($key): bool => !is_string($key))
                        ->thenInvalid('Locale cookie key should be a string')
                    ->end()
                ->end()
                ->scalarNode('locale_cookie_expires_in')
                    ->info('The time interval after which the locale cookie expires; null gives a session cookie')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function ($expiresIn): bool {
                            if ($expiresIn === null) {
                                return false;
                            }

                            if (!is_string($expiresIn)) {
                                return true;
                            }

                            $now = new DateTimeImmutable();
                            $future = $now->modify($expiresIn);

                            return $now >= $future;
                        })
                        ->thenInvalid('Locale cookie expiration should be null or a positive DateTime modifier string, for example "+2 months"')
                    ->end()
                ->end()
                ->booleanNode('locale_cookie_secure')
                    ->isRequired()
                    ->info('Whether or not the locale cookie should be secure')
                ->end()
                ->booleanNode('locale_cookie_http_only')
                    ->isRequired()
                    ->info('Whether or not the locale cookie should be HTTP only')
                ->end()
            ->end();
    }

    private function setupAttributeSupportConfiguration(
        ArrayNodeDefinition $rootNode,
    ): void {
        $rootNode
            ->children()
                ->arrayNode('attribute_support')
                    ->isRequired()
                    ->children()
                        ->scalarNode('email_to')
                            ->info('Email address to which attributes are sent')
                            ->isRequired()
                            ->validate()
                                ->ifTrue(fn($email): bool => !is_string($email))
                                ->thenInvalid('Email address to which attributes are sent should be a string')
                            ->end()
                            ->validate()
                                ->ifTrue(fn($email): bool => !filter_var($email, FILTER_VALIDATE_EMAIL))
                                ->thenInvalid('Email address to which attributes are sent should be valid')
                            ->end()
                        ->end()
                        ->scalarNode('email_from')
                            ->info('mail address from which attributes are sent')
                            ->isRequired()
                            ->validate()
                                ->ifTrue(fn($email): bool => !is_string($email))
                                ->thenInvalid('Email address from which attributes are sent should be a string')
                            ->end()
                            ->validate()
                                ->ifTrue(fn($email): bool => !filter_var($email, FILTER_VALIDATE_EMAIL))
                                ->thenInvalid('Email address from which attributes are sent should be valid')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function setupAttributeAggregationAttributeConfiguration(
        ArrayNodeDefinition $rootNode,
    ): void {

        $protoType = $rootNode
            ->children()
                ->arrayNode('attribute_aggregation_supported_attributes')
                    ->isRequired()
                    ->info('A list of supported attributes by Attribute Aggregation')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('type')
                    ->normalizeKeys(false)
                    ->prototype('array');

        $protoType
            ->children()
                ->scalarNode('logo_path')
                    ->info('The logo path of the AA attribute')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(fn($logoPath): bool => !is_string($logoPath))
                        ->thenInvalid('The logo path of the AA attribute should be a string')
                    ->end()
                ->end()
                ->scalarNode('connect_url')
                    ->info('The connect url of the AA attribute')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(fn($connectUrl): bool => !is_string($connectUrl))
                        ->thenInvalid('The connect url of the AA attribute should be a string')
                    ->end()
                ->end()
            ->end();
    }

    private function setupInformationRequestConfiguration(
        ArrayNodeDefinition $rootNode,
    ): void {
        $rootNode
            ->children()
                ->arrayNode('information_request')
                    ->isRequired()
                    ->children()
                        ->scalarNode('email_to')
                            ->info('Email address to which the information request results are sent')
                            ->isRequired()
                            ->validate()
                                ->ifTrue(fn($email): bool => !is_string($email))
                                ->thenInvalid('Email address to which information request results are sent should be a string')
                            ->end()
                            ->validate()
                                ->ifTrue(fn($email): bool => !filter_var($email, FILTER_VALIDATE_EMAIL))
                                ->thenInvalid('Email address to which information request results are sent should be valid')
                            ->end()
                        ->end()
                        ->scalarNode('email_from')
                            ->info('mail address from which information requests are sent')
                            ->isRequired()
                            ->validate()
                                ->ifTrue(fn($email): bool => !is_string($email))
                                ->thenInvalid('Email address from which information requests are sent should be a string')
                            ->end()
                            ->validate()
                                ->ifTrue(fn($email): bool => !filter_var($email, FILTER_VALIDATE_EMAIL))
                                ->thenInvalid('Email address from which information requests are sent should be valid')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
