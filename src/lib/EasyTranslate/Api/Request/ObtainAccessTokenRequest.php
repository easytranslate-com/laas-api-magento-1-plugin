<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Request;

use EasyTranslate\Api\Configuration;

class ObtainAccessTokenRequest extends AbstractRequest
{
    private const GRANT_TYPE = 'password';

    private const SCOPE = 'dashboard';

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
            'grant_type'    => self::GRANT_TYPE,
            'username'      => $this->configuration->getUsername(),
            'password'      => $this->configuration->getPassword(),
            'scope'         => self::SCOPE,
        ];
    }

    public function requiresAuthentication(): bool
    {
        return false;
    }
}
