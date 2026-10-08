<?php

declare(strict_types = 1);

/**
 * Copyright 2026 SURFnet B.V.
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

use Symfony\Component\HttpFoundation\Request;

/**
 * EngineBlock's /reset-remember-wayf endpoint redirects back to the My Profile page with
 * a `wayfReset` query parameter to signal whether a remembered-choice cookie was actually
 * removed or whether there was none to begin with. This resolver reads that parameter and
 * limits it to the values Profile knows how to give feedback about, so an unrecognised or
 * tampered-with value never triggers the feedback modal.
 */
final readonly class WayfResetResultResolver
{
    public const string RESULT_REMOVED = 'removed';
    public const string RESULT_NONE = 'none';

    private const array KNOWN_RESULTS = [self::RESULT_REMOVED, self::RESULT_NONE];

    public function resolve(
        Request $request,
    ): ?string {
        $result = $request->query->get('wayfReset');

        return in_array($result, self::KNOWN_RESULTS, true) ? $result : null;
    }
}
