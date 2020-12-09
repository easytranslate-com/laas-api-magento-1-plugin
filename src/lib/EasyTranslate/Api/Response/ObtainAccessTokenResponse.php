<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Response;

use EasyTranslate\Api\ApiException;

class ObtainAccessTokenResponse extends AbstractResponse
{
    /**
     * @var string
     */
    private $accessToken;

    public function mapFields(array $data): void
    {
        parent::mapFields($data);
        if (!isset($data['access_token'])) {
            throw new ApiException(sprintf('Invalid response data in response class %s', self::class));
        }
        $this->accessToken = $data['access_token'];
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }
}
