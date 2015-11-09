<?php

namespace OpenConext\Profile\Value;

use DateTime;
use DateTimeImmutable;
use OpenConext\Profile\Assert;
use OpenConext\Profile\Exception\InvalidArgumentException;
use OpenConext\Profile\Value\Consent\ServiceProvider;

final class Consent
{
    /**
     * @var ServiceProvider
     */
    private $serviceProvider;

    /**
     * @var DateTimeImmutable
     */
    private $consentGivenOn;

    /**
     * @var DateTimeImmutable
     */
    private $lastUsedOn;

    /**
     * @param ServiceProvider $serviceProvider
     * @param DateTimeImmutable $consentGivenOn
     * @param DateTimeImmutable $lastUsedOn
     */
    public function __construct(
        ServiceProvider $serviceProvider,
        DateTimeImmutable $consentGivenOn,
        DateTimeImmutable $lastUsedOn
    ) {
        $this->serviceProvider = $serviceProvider;
        $this->consentGivenOn  = $consentGivenOn;
        $this->lastUsedOn      = $lastUsedOn;
    }

    /**
     * @param Consent $other
     * @return bool
     */
    public function equals(Consent $other)
    {
        return $this->serviceProvider->equals($other->serviceProvider)
            && $this->consentGivenOn == $other->consentGivenOn
            && $this->lastUsedOn == $other->lastUsedOn;
    }

    /**
     * @return ServiceProvider
     */
    public function getServiceProvider()
    {
        return $this->serviceProvider;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getConsentGivenOn()
    {
        return $this->consentGivenOn;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getLastUsedOn()
    {
        return $this->lastUsedOn;
    }
}
