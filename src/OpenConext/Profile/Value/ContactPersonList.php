<?php

declare(strict_types = 1);

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

namespace OpenConext\Profile\Value;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OpenConext\Profile\Exception\OutOfRangeException;
use Stringable;
use Traversable;

final class ContactPersonList implements IteratorAggregate, Countable, Stringable
{
    /**
     * @var ContactPerson[]
     */
    private array $contactPersons = [];

    public function __construct(
        array $contactPersons,
    ) {
        foreach ($contactPersons as $contactPerson) {
            $this->initializeWith($contactPerson);
        }
    }

    private function initializeWith(
        ContactPerson $contactPerson,
    ): void {
        $this->contactPersons[] = $contactPerson;
    }

    public function filter(
        callable $predicate,
    ): ContactPersonList {
        return new ContactPersonList(
            array_filter(
                $this->contactPersons,
                fn(ContactPerson $contactPerson) => $predicate($contactPerson),
            ),
        );
    }

    /**
     * @return ContactPerson
     */
    public function first()
    {
        if (!isset($this->contactPersons[0])) {
            throw new OutOfRangeException('Cannot get the first Contact Person of an empty Contact Person List');
        }

        return $this->contactPersons[0];
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->contactPersons);
    }

    public function count(): int
    {
        return count($this->contactPersons);
    }

    public function __toString(): string
    {
        return implode(', ', $this->contactPersons);
    }
}
