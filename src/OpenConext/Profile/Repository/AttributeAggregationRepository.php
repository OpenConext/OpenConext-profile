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

namespace OpenConext\Profile\Repository;

use OpenConext\Profile\Exception\InvalidArgumentException;
use OpenConext\Profile\Value\AttributeAggregation\AttributeAggregationAttributesList;

interface AttributeAggregationRepository
{
    /**
     * @param string $userId
     * @return AttributeAggregationAttributesList
     * @throws InvalidArgumentException When $userId is not a non-empty string
     */
    public function findAllFor($userId);

    /**
     * Removes an account by providing the account id. The method returns a boolean value reflecting the
     * result of the API call.
     *
     * @param $accountId
     * @return bool
     */
    public function unsubscribeAccount($accountId);
}
