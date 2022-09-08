<?php

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

    /**
     * @param string $environment
     * @param string $clientId
     * @param string $clientSecret
     * @param string $username
     * @param string $password
     */
    public function __construct(
        $environment,
        $clientId,
        $clientSecret,
        $username,
        $password
    ) {
        $environment = (string) $environment;
        $clientId = (string) $clientId;
        $clientSecret = (string) $clientSecret;
        $username = (string) $username;
        $password = (string) $password;
        $this->environment  = $environment;
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->username     = $username;
        $this->password     = $password;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
