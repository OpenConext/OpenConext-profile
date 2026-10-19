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

use OpenConext\ProfileBundle\Service\WayfResetResultResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class WayfResetResultResolverTest extends TestCase
{
    /**
     * @test
     * @group Service
     */
    public function it_resolves_the_removed_result(): void
    {
        $resolver = new WayfResetResultResolver();

        $result = $resolver->resolve(Request::create('/my-profile?wayfReset=removed'));

        $this->assertSame(WayfResetResultResolver::RESULT_REMOVED, $result);
    }

    /**
     * @test
     * @group Service
     */
    public function it_resolves_the_none_result(): void
    {
        $resolver = new WayfResetResultResolver();

        $result = $resolver->resolve(Request::create('/my-profile?wayfReset=none'));

        $this->assertSame(WayfResetResultResolver::RESULT_NONE, $result);
    }

    /**
     * @test
     * @group Service
     */
    public function it_returns_null_when_the_query_parameter_is_missing(): void
    {
        $resolver = new WayfResetResultResolver();

        $result = $resolver->resolve(Request::create('/my-profile'));

        $this->assertNull($result);
    }

    /**
     * @test
     * @group Service
     */
    public function it_returns_null_for_an_unrecognised_value(): void
    {
        $resolver = new WayfResetResultResolver();

        $result = $resolver->resolve(Request::create('/my-profile?wayfReset=<script>alert(1)</script>'));

        $this->assertNull($result);
    }
}
