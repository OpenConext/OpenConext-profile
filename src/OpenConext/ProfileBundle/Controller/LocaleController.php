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

use OpenConext\ProfileBundle\Form\Type\SwitchLocaleType;
use OpenConext\ProfileBundle\Profile\Command\ChangeLocaleCommand;
use OpenConext\ProfileBundle\Security\Guard;
use OpenConext\ProfileBundle\Service\UserService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LocaleController extends AbstractController
{

    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly UserService $userService,
        private readonly Guard $guard,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function switchLocale(Request $request): RedirectResponse
    {
        $this->guard->userIsLoggedIn();

        $this->logger->info('User requested to switch locale');

        $returnUrl = $request->query->get('return-url');

        // Return URLs generated by us always include a path (ie. at least a forward slash)
        // @see https://github.com/symfony/symfony/blob/master/src/Symfony/Component/HttpFoundation/Request.php#L878
        $domain = $request->getSchemeAndHttpHost() . '/';

        if (!str_starts_with($returnUrl, $domain)) {
            $this->logger->error(sprintf(
                'Illegal return-url ("%s") for redirection after changing locale, aborting request',
                $returnUrl,
            ));
            throw new BadRequestHttpException('Invalid return-url given');
        }

        $command = new ChangeLocaleCommand();
        $form = $this->formFactory->create(SwitchLocaleType::class, $command, [])->handleRequest($request);

        $this->logger->notice(sprintf(
            'Switching locale from "%s" to "%s"',
            $request->getLocale(),
            $command->newLocale,
        ));

        if ($form->isValid()) {
            $this->userService->changeLocale($command);
            $this->addFlash('success', 'profile.locale.locale_change_success');

            $this->logger->notice(sprintf(
                'Successfully switched locale from "%s" to "%s"',
                $request->getLocale(),
                $command->newLocale,
            ));
        } else {
            $this->addFlash('error', 'profile.locale.locale_change_fail');

            $this->logger->error('Locale not switched: the switch locale form contained invalid data');
        }

        return new RedirectResponse($returnUrl);
    }
}
