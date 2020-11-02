<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Request;

use EasyTranslate\Api\Configuration;

class ObtainAccessTokenRequest extends AbstractRequest
{
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getType(): string
    {
        return self::TYPE_POST;
    }
    public function getResource(): string
    {
        return 'oauth/token';
    }

    public function getData(): array
    {
        return [
            'client_id'     => $this->configuration->getClientId(),
            'client_secret' => $this->configuration->getClientSecret(),
            'grant_type'    => 'password',
            'username'      => $this->configuration->getUsername(),
            'password'      => $this->configuration->getPassword(),
            'scope'         => 'dashboard',
        ];
    }

    public function requiresAuthentication(): bool
    {
        return false;
    }
}
