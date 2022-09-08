<?php

namespace EasyTranslate\Api\Request;

class FetchAuthenticatedUserRequest extends AbstractRequest
{
    /**
     * @return string
     */
    public function getResource()
    {
        return 'api/v1/user';
    }
}
