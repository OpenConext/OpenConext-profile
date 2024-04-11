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

namespace OpenConext\ProfileBundle\Controller;

use OpenConext\ProfileBundle\Service\UserService;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDefinition;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class IntroductionController extends AbstractController
{

    public function __construct(
        private readonly UserService $userService,
        private readonly Environment $templateEngine,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function overview(): Response
    {
        $this->logger->info('Showing Introduction page');
        $attributeDefinition = new AttributeDefinition(
            'givenName',
            'urn:mace:dir:attribute-def:givenName',
            'urn:oid:2.5.4.42',
        );
        try {
            $userName = $this->userService
                ->getUser()
                ->getAttributes()
                ->getAttributeByDefinition($attributeDefinition)
                ->getValue()[0];
        } catch (RuntimeException) {
            $this->logger->info("Unable to retrieve the givenName attribute. It is not present in the attribute set");
            $userName = false;
        }
        return new Response($this->templateEngine->render('@OpenConextProfile/Introduction/overview.html.twig', [
            'userName' => $userName,
        ]));
    }
}
