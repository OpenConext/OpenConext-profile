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

use OpenConext\ProfileBundle\Service\ConsentService;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDictionary;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Templating\EngineInterface;

class ServicesController
{
    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * @var AttributeDictionary
     */
    private $attributeDictionary;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var ConsentService
     */
    private $consentService;

    /**
     * @param EngineInterface $templateEngine
     * @param AttributeDictionary $attributeDictionary
     * @param TokenStorageInterface $tokenStorage
     * @param ConsentService $consentService
     */
    public function __construct(
        EngineInterface $templateEngine,
        AttributeDictionary $attributeDictionary,
        TokenStorageInterface $tokenStorage,
        ConsentService $consentService
    ) {
        $this->templateEngine = $templateEngine;
        $this->attributeDictionary = $attributeDictionary;
        $this->tokenStorage = $tokenStorage;
        $this->consentService = $consentService;
    }

    public function overviewAction()
    {
        $userIdDefinition = $this->attributeDictionary
            ->getAttributeDefinitionByUrn('urn:oid:1.3.6.1.4.1.1076.20.40.40.1');

        $user = $this->tokenStorage->getToken()->getUser();

        /** @var \Surfnet\SamlBundle\SAML2\Attribute\AttributeSet $attributeSet */
        $attributeSet = $user->getAttributes();
        $userId = $attributeSet->getAttributeByDefinition($userIdDefinition);

        $consents = $this->consentService->findAllFor($userId->getValue());

        return new Response($this->templateEngine->render(
            'OpenConextProfileBundle:Services:overview.html.twig',
            ['consents' => $consents]
        ));
    }
}
