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

namespace OpenConext\ProfileBundle\DependencyInjection;

use DateTimeImmutable;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('open_conext_profile');

        $rootNode
            ->children()
                ->scalarNode('engine_block_entity_id')
                    ->info('The EntityID of EngineBlock')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function ($entityId) {
                            return !is_string($entityId);
                        })
                        ->thenInvalid('EngineBlock EntityID should be a string')
                    ->end()
                ->end()
            ->end();

        $this->setupLocaleConfiguration($rootNode);
        $this->setupAttributeSupportConfiguration($rootNode);

        return $treeBuilder;
    }

    private function setupLocaleConfiguration(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('locales')
                    ->info('The available application locales')
                    ->isRequired()
                    ->prototype('scalar')
                        ->validate()
                            ->ifTrue(function ($locale) {
                                return !is_string($locale);
                            })
                            ->thenInvalid('Available application locales should be strings')
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('default_locale')
                    ->info('The default application locale')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function ($locale) {
                            return !is_string($locale);
                        })
                        ->thenInvalid('Default application locale should be a string')
                    ->end()
                ->end()
                ->scalarNode('locale_cookie_domain')
                    ->info('The domain for which the locale cookie is set')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function ($domain) {
                            return !is_string($domain);
                        })
                        ->thenInvalid('Locale cookie domain should be a string')
                    ->end()
                ->end()
                ->scalarNode('locale_cookie_key')
                    ->info('The key for which the locale cookie value is set')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function ($key) {
                            return !is_string($key);
                        })
                        ->thenInvalid('Locale cookie key should be a string')
                    ->end()
                ->end()
                ->scalarNode('locale_cookie_expires_in')
                    ->info('The time interval after which the locale cookie expires; null gives a session cookie')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function ($expiresIn) {
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

    private function setupAttributeSupportConfiguration(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('attribute_support')
                    ->isRequired()
                    ->children()
                        ->scalarNode('email_to')
                            ->info('Email address to which attributes are sent')
                            ->isRequired()
                            ->validate()
                                ->ifTrue(function ($email) {
                                    return !is_string($email);
                                })
                                ->thenInvalid('Email address to which attributes are sent should be a string')
                            ->end()
                            ->validate()
                                ->ifTrue(function ($email) {
                                    return !filter_var($email, FILTER_VALIDATE_EMAIL);
                                })
                                ->thenInvalid('Email address to which attributes are sent should be valid')
                            ->end()
                        ->end()
                        ->scalarNode('email_from')
                            ->info('mail address from which attributes are sent')
                            ->isRequired()
                            ->validate()
                                ->ifTrue(function ($email) {
                                    return !is_string($email);
                                })
                                ->thenInvalid('Email address from which attributes are sent should be a string')
                            ->end()
                            ->validate()
                                ->ifTrue(function ($email) {
                                    return !filter_var($email, FILTER_VALIDATE_EMAIL);
                                })
                                ->thenInvalid('Email address from which attributes are sent should be valid')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
