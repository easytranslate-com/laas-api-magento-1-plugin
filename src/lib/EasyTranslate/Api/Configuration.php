<?php

declare(strict_types=1);

namespace EasyTranslate\Api;

class Configuration
{
    /**
     * @var string
     */
    private $environment;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    public function __construct(
        string $environment,
        string $clientId,
        string $clientSecret,
        string $username,
        string $password
    ) {
        $this->environment  = $environment;
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->username     = $username;
        $this->password     = $password;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
