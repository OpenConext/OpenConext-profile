<?php

declare(strict_types = 1);

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

namespace OpenConext\Infrastructure\Monolog\Formatter;

use DateTimeImmutable;
use Monolog\Formatter\JsonFormatter as MonologJsonFormatter;
use Monolog\LogRecord;

/**
 * Formats incoming records into a one-line JSON string. Includes only the channel. level, message, context and extra
 * fields of records, omitting the datetime for example.
 */
class JsonFormatter extends MonologJsonFormatter
{
    public function format(
        LogRecord $record,
    ): string {
        return parent::format($this->mapRecord($record));
    }

    public function formatBatch(
        array $records,
    ): string {
        return parent::formatBatch(
            array_map(
                fn(LogRecord $record): LogRecord => $this->mapRecord($record),
                $records,
            ),
        );
    }

    private function mapRecord(
        LogRecord $record,
    ): LogRecord {
        return new LogRecord(
            new DateTimeImmutable(),
            $record->channel,
            $record->level,
            $record->message,
            $record->context,
            $record->extra,
        );
    }
}
