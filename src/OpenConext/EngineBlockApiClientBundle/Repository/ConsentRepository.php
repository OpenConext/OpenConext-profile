<?php

namespace OpenConext\EngineBlockApiClientBundle\Repository;

use OpenConext\EngineBlockApiClientBundle\Http\JsonApiClient;
use OpenConext\EngineBlockApiClientBundle\Value\ConsentListFactory;
use OpenConext\Profile\Assert;
use OpenConext\Profile\Repository\ConsentRepository as ConsentRepositoryInterface;

final class ConsentRepository implements ConsentRepositoryInterface
{
    /**
     * @var JsonApiClient
     */
    private $apiClient;

    public function __construct(JsonApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function findAllFor($userId)
    {
        Assert::string($userId, 'User ID "%s" expected to be string, type %s given.');
        Assert::notEmpty($userId, 'User ID "%s" is empty, but non empty value was expected.');

        $consentListJson = $this->apiClient->read('consent/%s', [$userId]);

        return ConsentListFactory::create($consentListJson);
    }
}
