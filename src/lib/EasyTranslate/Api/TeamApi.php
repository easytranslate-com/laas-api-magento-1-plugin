<?php

declare(strict_types=1);

namespace EasyTranslate\Api;

use EasyTranslate\Api\Request\FetchAuthenticatedUserRequest;
use EasyTranslate\Api\Response\FetchAuthenticatedUserResponse;

class TeamApi extends AbstractApi
{
    public function getUser(): FetchAuthenticatedUserResponse
    {
        $request = new FetchAuthenticatedUserRequest();
        $data    = $this->sendRequest($request);

        return new FetchAuthenticatedUserResponse($data);
    }
}
