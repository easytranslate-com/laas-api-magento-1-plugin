<?php

namespace EasyTranslate\Api;

use EasyTranslate\Api\Request\FetchAuthenticatedUserRequest;
use EasyTranslate\Api\Response\FetchAuthenticatedUserResponse;

class TeamApi extends AbstractApi
{
    /**
     * @return \EasyTranslate\Api\Response\FetchAuthenticatedUserResponse
     */
    public function getUser()
    {
        $request = new FetchAuthenticatedUserRequest();
        $data    = $this->sendRequest($request);

        return new FetchAuthenticatedUserResponse($data);
    }
}
