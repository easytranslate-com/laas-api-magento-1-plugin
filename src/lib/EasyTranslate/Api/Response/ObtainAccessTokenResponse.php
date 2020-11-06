<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Response;

class ObtainAccessTokenResponse extends AbstractResponse
{
    /**
     * @var string
     */
    private $accessToken;

    public function mapFields(array $data): void
    {
        if (isset($data['access_token'])) {
            $this->accessToken = $data['access_token'];
        }
        parent::mapFields($data);
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }
}
