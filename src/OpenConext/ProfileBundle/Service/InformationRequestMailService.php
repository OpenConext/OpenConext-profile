<?php

declare(strict_types = 1);

/**
 * Copyright 2017 SURFnet B.V.
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
use OpenConext\ProfileBundle\Attribute\AttributeFilter;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface as Mailer;

final readonly class InformationRequestMailService
{
    public function __construct(
        private EmailAddress $mailFrom,
        private EmailAddress $mailTo,
        private Mailer $mailer,
        private UserService $userService,
        private AttributeFilter $attributeFilter,
    ) {
    }

    public function sendInformationRequestMail(): void
    {
        $user = $this->userService->getUser();
        $attributes = $this->attributeFilter->filter($user->getAttributes());
        $email = (new TemplatedEmail())
            ->from($this->mailFrom->getEmailAddress())
            ->to($this->mailTo->getEmailAddress())
            ->subject(sprintf('Information request for user: %s', $user->getId()))
            ->htmlTemplate('@OpenConextProfile/InformationRequest/email.html.twig')
            ->context(['attributes' => $attributes]);
        $this->mailer->send($email);
    }
}
