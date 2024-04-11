<?php

/**
 * Copyright 2018 SURFnet B.V.
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

namespace OpenConext\UserLifecycleApiClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('open_conext_user_lifecycle_api_client');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->booleanNode('enabled')
                    ->isRequired()
                    ->info('Give user the option to download their data')
                ->end()
                ->arrayNode('http_client')
                    ->children()
                        ->scalarNode('base_url')
                            ->info('The base URL of the user lifecycle API host')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->validate()
                                ->ifTrue(fn($baseUrl) => !is_string($baseUrl))
                                ->thenInvalid('The user lifecycle API base URL should be a string')
                            ->end()
                            ->validate()
                                ->ifTrue(fn($baseUrl) => !filter_var($baseUrl, FILTER_VALIDATE_URL))
                                ->thenInvalid('The user lifecycle base URL should be a valid URL')
                            ->end()
                            ->validate()
                                ->ifTrue(function ($baseUrl): bool {
                                    $path = parse_url($baseUrl, PHP_URL_PATH);

                                    if ($path === null) {
                                        return true;
                                    }

                                    return ! str_ends_with($path, '/');
                                })
                                ->thenInvalid('The user lifecycle base URL must end in a forward slash')
                            ->end()
                        ->end()
                        ->booleanNode('verify_ssl')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('username')
                            ->info('The username of the API user')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->validate()
                                ->ifTrue(fn($username) => !is_string($username))
                                ->thenInvalid('The user lifecycle username should be a string')
                            ->end()
                        ->end()
                        ->scalarNode('password')
                            ->info('The password of the API user')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->validate()
                                ->ifTrue(fn($password) => !is_string($password))
                                ->thenInvalid('The user lifecycle password should be a string')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
