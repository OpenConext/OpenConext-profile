<?php

/**
 * Copyright 2024 SURFnet B.V.
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
/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenConext\ProfileBundle\Session;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use function array_key_exists;
use function count;

/**
 * This class is copied from SF4, to keep compatibility
 * Not sure if we're going to need it
 *
 * @SuppressWarnings(PHPMD)
 */
class NamespacedAttributeBag extends AttributeBag
{
    /**
     * @param string $storageKey         Session storage key
     * @param string $namespaceCharacter Namespace character to use in keys
     */
    public function __construct(
        string $storageKey = '_sf2_attributes',
        private readonly string $namespaceCharacter = '/',
    ) {
        parent::__construct($storageKey);
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $name): bool
    {
        // reference mismatch: if fixed, re-introduced in array_key_exists; keep as it is
        $attributes = $this->resolveAttributePath($name);
        $name = $this->resolveKey($name);

        if (null === $attributes) {
            return false;
        }

        return array_key_exists($name, $attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $name, $default = null): mixed
    {
        // reference mismatch: if fixed, re-introduced in array_key_exists; keep as it is
        $attributes = $this->resolveAttributePath($name);
        $name = $this->resolveKey($name);

        if (null === $attributes) {
            return $default;
        }

        return array_key_exists($name, $attributes) ? $attributes[$name] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $name, mixed $value): void
    {
        $attributes = &$this->resolveAttributePath($name, true);
        $name = $this->resolveKey($name);
        $attributes[$name] = $value;
    }

    public function remove(string $name): mixed
    {
        $retval = null;
        $attributes = &$this->resolveAttributePath($name);
        $name = $this->resolveKey($name);
        if (null !== $attributes && array_key_exists($name, $attributes)) {
            $retval = $attributes[$name];
            unset($attributes[$name]);
        }

        return $retval;
    }

    /**
     * Resolves a path in attributes property and returns it as a reference.
     *
     * This method allows structured namespacing of session attributes.
     */
    protected function &resolveAttributePath(string $name, bool $writeContext = false): ?array
    {
        $array = &$this->attributes;
        $name = (str_starts_with($name, $this->namespaceCharacter)) ? substr($name, 1) : $name;

        // Check if there is anything to do, else return
        if (!$name) {
            return $array;
        }

        $parts = explode($this->namespaceCharacter, $name);
        if (count($parts) < 2) {
            if (!$writeContext) {
                return $array;
            }

            $array[$parts[0]] = [];

            return $array;
        }

        unset($parts[count($parts) - 1]);

        foreach ($parts as $part) {
            if (null !== $array && !array_key_exists($part, $array)) {
                if (!$writeContext) {
                    $null = null;

                    return $null;
                }

                $array[$part] = [];
            }

            $array = &$array[$part];
        }

        return $array;
    }

    /**
     * Resolves the key from the name.
     *
     * This is the last part in a dot separated string.
     */
    protected function resolveKey(string $name): string
    {
        if (false !== $pos = strrpos($name, $this->namespaceCharacter)) {
            $name = substr($name, $pos + 1);
        }

        return $name;
    }
}
