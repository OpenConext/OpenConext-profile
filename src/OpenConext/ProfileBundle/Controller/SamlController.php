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

use Psr\Log\LoggerInterface;
use Surfnet\SamlBundle\Http\XMLResponse;
use Surfnet\SamlBundle\Metadata\MetadataFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

class SamlController extends AbstractController
{
    public function __construct(
        private readonly MetadataFactory $metadataFactory,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route(
        path: '/authentication/consume-assertion',
        name: 'profile.saml_consume_assertion',
        methods: ['POST'],
        schemes: ['https'],
    )]
    public function consumeAssertion(): never
    {
        throw new BadRequestHttpException('Unexpected request sent to ACS');
    }

    #[Route(
        path: '/authentication/metadata',
        name: 'profile.saml_metadata',
        methods: ['GET'],
        schemes: ['https'],
    )]
    public function metadata(): XMLResponse
    {
        $this->logger->info('Showing SAML metadata');

        return new XMLResponse((string) $this->metadataFactory->generate());
    }
}
