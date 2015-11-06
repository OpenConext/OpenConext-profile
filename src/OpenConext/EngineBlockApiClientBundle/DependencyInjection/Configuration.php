<?php

namespace OpenConext\EngineBlockApiClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('open_conext_engine_block_api_client');

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
