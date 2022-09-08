<?php

namespace EasyTranslate\Api\Request;

use EasyTranslate\Api\Configuration;

class ObtainAccessTokenRequest extends AbstractRequest
{
    const GRANT_TYPE = 'password';

    const SCOPE = 'dashboard';

    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_POST;
    }

    /**
     * @return string
     */
    public function getResource()
    {
        return 'oauth/token';
    }

    /**
     * @return mixed[]
     */
    public function getData()
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

    /**
     * @return bool
     */
    public function requiresAuthentication()
    {
        return false;
    }
}
