<?php

namespace OpenConext\EngineBlockApiClientBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class OpenConextEngineBlockApiClientExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->getDefinition('openconext_eb_api.guzzle')
            ->replaceArgument(0, [
                'base_uri' => $config['http_client']['base_url'],
                'verify' => $config['http_client']['verify_ssl'],
                'auth' => [
                    $config['http_client']['username'],
                    $config['http_client']['password'],
                    'basic'
                ],
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);
    }
}
