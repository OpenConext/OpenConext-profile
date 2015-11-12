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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ServicesController extends Controller
{
    public function overviewAction()
    {
        /** @var \Surfnet\SamlBundle\SAML2\Attribute\AttributeDictionary $attributeDictionary */
        $attributeDictionary = $this->get('surfnet_saml.saml.attribute_dictionary');

        // Get the userId for the user
        $userIdDefinition = $attributeDictionary->getAttributeDefinitionByUrn('urn:oid:1.3.6.1.4.1.1076.20.40.40.1');

        $user = $this->getUser();

        /** @var \Surfnet\SamlBundle\SAML2\Attribute\AttributeSet $attributeSet */
        $attributeSet = $user->getAttributes();
        $userId = $attributeSet->getAttributeByDefinition($userIdDefinition);

        /** @var \OpenConext\ProfileBundle\Service\ConsentService $consentService */
        $consentService = $this->get('profile.service.consent');
        $consents = $consentService->findAllFor($userId->getValue());

        return $this->render('OpenConextProfileBundle:Services:overview.html.twig', ['consents' => $consents]);
    }
}
