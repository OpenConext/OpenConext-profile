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

namespace OpenConext\EngineBlockApiClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('open_conext_engine_block_api_client');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('http_client')
                    ->isRequired()
                    ->children()
                        ->scalarNode('base_url')
                            ->info('The base URL of the EngineBlock API host')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->validate()
                                ->ifTrue(function ($baseUrl) {
                                    return !is_string($baseUrl);
                                })
                                ->thenInvalid('The EngineBlock API base URL should be a string')
                            ->end()
                            ->validate()
                                ->ifTrue(function ($baseUrl) {
                                    return !filter_var($baseUrl, FILTER_VALIDATE_URL);
                                })
                                ->thenInvalid('The EngineBlock API base URL should be a valid URL')
                            ->end()
                            ->validate()
                                ->ifTrue(function ($baseUrl) {
                                    $path = parse_url($baseUrl, PHP_URL_PATH);

                                    return $path[strlen($path)-1] !== '/';
                                })
                                ->thenInvalid('The EngineBlock API base URL must end in a forward slash')
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
                                ->ifTrue(function ($username) {
                                    return !is_string($username);
                                })
                                ->thenInvalid('The EngineBlock API username should be a string')
                            ->end()
                        ->end()
                        ->scalarNode('password')
                            ->info('The password of the API user')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->validate()
                                ->ifTrue(function ($password) {
                                    return !is_string($password);
                                })
                                ->thenInvalid('The EngineBlock API password should be a string')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
