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

namespace OpenConext\ProfileBundle\Security\Factory;

use OpenConext\ProfileBundle\Security\Authentication\Provider\SamlProvider;
use OpenConext\ProfileBundle\Security\Firewall\SamlListener;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AuthenticatorFactoryInterface;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SamlFactory implements AuthenticatorFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint): array
    {
        $providerId = 'security.authentication.provider.saml.' . $id;
        $container->setDefinition(
            $providerId,
            new ChildDefinition(SamlProvider::class),
        );
        $listenerId = 'security.authentication.listener.saml.' . $id;
        $container->setDefinition(
            $listenerId,
            new ChildDefinition(SamlListener::class),
        );

        return [$providerId, $listenerId, $defaultEntryPoint];
    }

    public function getPosition(): string
    {
        return 'pre_auth';
    }

    public function getKey(): string
    {
        return 'saml';
    }

    public function addConfiguration(NodeDefinition $builder): void
    {
    }

    public function getPriority(): int
    {
        return -10;
    }

    public function createAuthenticator(
        ContainerBuilder $container,
        string $firewallName,
        array $config,
        string $userProviderId,
    ): string|array {
        return $config;
    }
}
