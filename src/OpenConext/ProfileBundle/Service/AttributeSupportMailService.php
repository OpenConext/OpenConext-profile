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

use OpenConext\Profile\Value\EmailAddress;
use Swift_Mailer as Mailer;
use Swift_Message as Message;
use Symfony\Component\Templating\EngineInterface;

final class AttributeSupportMailService
{
    /**
     * @var EmailAddress
     */
    private $mailFrom;

    /**
     * @var EmailAddress
     */
    private $mailTo;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(
        EmailAddress $mailFrom,
        EmailAddress $mailTo,
        Mailer $mailer,
        EngineInterface $templateEngine,
        UserService $userService
    ) {
        $this->mailFrom       = $mailFrom;
        $this->mailTo         = $mailTo;
        $this->mailer         = $mailer;
        $this->templateEngine = $templateEngine;
        $this->userService    = $userService;
    }

    public function sendAttributeSupportMail()
    {
        $user = $this->userService->getUser();

        $body = $this->templateEngine->render(
            'OpenConextProfileBundle:AttributeSupport:email.html.twig',
            ['attributes' => $user->getAttributes()]
        );

        /** @var Message $message */
        $message = $this->mailer->createMessage();
        $message
            ->setFrom($this->mailFrom->getEmailAddress())
            ->setTo($this->mailTo->getEmailAddress())
            ->setSubject(sprintf('Personal debug info of %s', $user->getId()))
            ->setBody($body, 'text/html', 'utf-8');

        $this->mailer->send($message);
    }
}
