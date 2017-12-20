<?php

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

namespace OpenConext\EngineBlockApiClientBundle\HealthCheck;

use Exception;
use OpenConext\MonitorBundle\HealthCheck\HealthCheckInterface;
use OpenConext\MonitorBundle\HealthCheck\HealthReportInterface;
use OpenConext\MonitorBundle\Value\HealthReport;
use OpenConext\Profile\Repository\ConsentRepository;

class ApiHealthCheck implements HealthCheckInterface
{
    /**
     * @var ConsentRepository
     */
    private $repository;

    public function __construct(ConsentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param HealthReportInterface $report
     * @return HealthReportInterface
     */
    public function check(HealthReportInterface $report)
    {
        try {
            $this->repository->findAllFor('a73b204b8d896a55b34e41e71f0138f81e81f4da');
        } catch (Exception $e) {
            return HealthReport::buildStatusDown(
                sprintf(
                    'EngineBlock API is not responding as expected. Message "%s" of type "%s".',
                    $e->getMessage(),
                    get_class($e)
                )
            );
        }
        return $report;
    }
}
