<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Callback\DataConverter;

use EasyTranslate\Api\Response\TaskCompletedResponse;

class TaskCompletedConverter
{
    public function convert(array $data): TaskCompletedResponse
    {
        return new TaskCompletedResponse($data);
    }
}
