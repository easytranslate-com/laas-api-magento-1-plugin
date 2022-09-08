<?php

namespace EasyTranslate\Api\Response;

use EasyTranslate\Api\ApiException;

class ObtainAccessTokenResponse extends AbstractResponse
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @param mixed[] $data
     * @return void
     */
    public function mapFields($data)
    {
        parent::mapFields($data);
        if (!isset($data['access_token'])) {
            throw new ApiException(sprintf('Invalid response data in response class %s', self::class));
        }
        $this->accessToken = $data['access_token'];
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}
