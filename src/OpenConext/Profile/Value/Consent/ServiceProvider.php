<?php

namespace OpenConext\Profile\Value\Consent;

use OpenConext\EngineBlockApiClientBundle\Exception\LogicException;
use OpenConext\Profile\Value\DisplayName;
use OpenConext\Profile\Value\EmailAddress;
use OpenConext\Profile\Value\Entity;
use OpenConext\Profile\Value\Url;

final class ServiceProvider
{
    /**
     * @var Entity
     */
    private $entity;

    /**
     * @var DisplayName
     */
    private $displayName;

    /**
     * @var Url|null
     */
    private $eulaUrl;

    /**
     * @var EmailAddress|null
     */
    private $supportEmail;

    public function __construct(
        Entity $entity,
        DisplayName $displayName,
        Url $eulaUrl = null,
        EmailAddress $supportEmail = null
    ) {
        $this->entity       = $entity;
        $this->displayName  = $displayName;
        $this->eulaUrl      = $eulaUrl;
        $this->supportEmail = $supportEmail;
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return DisplayName
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @return bool
     */
    public function hasEulaUrl()
    {
        return $this->eulaUrl !== null;
    }

    /**
     * @return Url
     */
    public function getEulaUrl()
    {
        if (!$this->hasEulaUrl()) {
            throw new LogicException('Service provider has no EULA url');
        }

        return $this->eulaUrl;
    }

    /**
     * @return bool
     */
    public function hasSupportEmail()
    {
        return $this->supportEmail !== null;
    }

    /**
     * @return EmailAddress
     */
    public function getSupportEmail()
    {
        if (!$this->hasSupportEmail()) {
            throw new LogicException('Service provider has no support e-mail address');
        }

        return $this->supportEmail;
    }

    /**
     * @param ServiceProvider $other
     * @return bool
     */
    public function equals(ServiceProvider $other)
    {
        return $this->entity->equals($other->entity)
            && $this->displayName->equals($other->displayName)
            && ($this->eulaUrl === $other->eulaUrl
                || $this->eulaUrl && $other->eulaUrl && $this->eulaUrl->equals($other->eulaUrl))
            && ($this->supportEmail === $other->supportEmail
                || $this->supportEmail && $other->supportEmail && $this->supportEmail->equals($other->supportEmail));
    }
}
