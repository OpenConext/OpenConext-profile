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

namespace OpenConext\ProfileBundle\Service;

use OpenConext\Profile\Repository\ConsentRepository;
use OpenConext\Profile\Value\ConsentList;
use OpenConext\ProfileBundle\Security\Authentication\Entity\User;
use Psr\Log\LoggerInterface;
use Surfnet\SamlBundle\SAML2\Attribute\AttributeDefinition;

final class ConsentService
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ConsentRepository
     */
    private $consentRepository;

    /**
     * @var AttributeDefinition
     */
    private $identifyingAttribute;

    public function __construct(
        LoggerInterface $logger,
        ConsentRepository $consentRepository,
        AttributeDefinition $identifyingAttribute
    ) {
        $this->logger               = $logger;
        $this->consentRepository    = $consentRepository;
        $this->identifyingAttribute = $identifyingAttribute;
    }

    /**
     * @param User $user
     * @return ConsentList|null
     */
    public function findAllFor(User $user)
    {
        if (!$user->getAttributes()->containsAttributeDefinedBy($this->identifyingAttribute)) {
            $message = sprintf(
                'Cannot get consent list for user: user does not have identifying attribute "%s"',
                $this->identifyingAttribute->getName()
            );

            $this->logger->error($message);

            return null;
        }

        $userIdentifier   = $user->getAttributes()->getAttributeByDefinition($this->identifyingAttribute);
        $identifyingValue = $userIdentifier->getValue();

        if (!is_string($identifyingValue)) {
            $message = sprintf(
                'In order to get the consent list for a user, the identifying attribute must have a string value. "%s"'
                . 'given for identifying attribute "%s"',
                gettype($identifyingValue),
                $this->identifyingAttribute->getName()
            );

            $this->logger->error($message);

            return null;
        }

        return $this->consentRepository->findAllFor($identifyingValue);
    }
}
