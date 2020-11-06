<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Request;

class FetchAuthenticatedUserRequest extends AbstractRequest
{
    public function getResource(): string
    {
        return 'api/v1/user';
    }
}
