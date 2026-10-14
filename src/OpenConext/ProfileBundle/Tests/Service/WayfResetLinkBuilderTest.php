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

namespace OpenConext\ProfileBundle\Tests\Service;

use OpenConext\ProfileBundle\Service\WayfResetLinkBuilder;
use PHPUnit\Framework\TestCase;

class WayfResetLinkBuilderTest extends TestCase
{
    /**
     * @test
     * @group Service
     */
    public function it_appends_the_redirect_parameter_to_a_plain_url(): void
    {
        $builder = new WayfResetLinkBuilder();

        $link = $builder->build(
            'https://engine.dev.openconext.local/reset-remember-wayf',
            'https://profile.dev.openconext.local/my-profile',
        );

        $this->assertSame(
            'https://engine.dev.openconext.local/reset-remember-wayf?redirect=https%3A%2F%2Fprofile.dev.openconext.local%2Fmy-profile',
            $link,
        );
    }

    /**
     * @test
     * @group Service
     */
    public function it_url_encodes_special_characters_in_the_return_url(): void
    {
        $builder = new WayfResetLinkBuilder();

        $link = $builder->build(
            'https://engine.dev.openconext.local/reset-remember-wayf',
            'https://profile.dev.openconext.local/my-profile?foo=bar&baz=1',
        );

        $this->assertSame(
            'https://engine.dev.openconext.local/reset-remember-wayf?redirect='
            . rawurlencode('https://profile.dev.openconext.local/my-profile?foo=bar&baz=1'),
            $link,
        );
    }

    /**
     * @test
     * @group Service
     */
    public function it_uses_an_ampersand_when_the_wayf_reset_url_already_has_a_query_string(): void
    {
        $builder = new WayfResetLinkBuilder();

        $link = $builder->build(
            'https://engine.dev.openconext.local/reset-remember-wayf?debug=1',
            'https://profile.dev.openconext.local/my-profile',
        );

        $this->assertSame(
            'https://engine.dev.openconext.local/reset-remember-wayf?debug=1&redirect=https%3A%2F%2Fprofile.dev.openconext.local%2Fmy-profile',
            $link,
        );
    }
}
