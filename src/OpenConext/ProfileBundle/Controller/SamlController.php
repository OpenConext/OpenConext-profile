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

namespace OpenConext\ProfileBundle\Controller;

use Psr\Log\LoggerInterface;
use Surfnet\SamlBundle\Http\XMLResponse;
use Surfnet\SamlBundle\Metadata\MetadataFactory;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SamlController
{
    /**
     * @var MetadataFactory
     */
    private $metadataFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param MetadataFactory $metadataFactory
     * @param LoggerInterface $logger
     */
    public function __construct(MetadataFactory $metadataFactory, LoggerInterface $logger)
    {
        $this->metadataFactory = $metadataFactory;
        $this->logger = $logger;
    }

    public function consumeAssertionAction()
    {
        throw new BadRequestHttpException('Unexpected request sent to ACS');
    }

    /**
     * @return XMLResponse
     */
    public function metadataAction()
    {
        $this->logger->info('Showing SAML metadata');

        return new XMLResponse($this->metadataFactory->generate());
    }
}
