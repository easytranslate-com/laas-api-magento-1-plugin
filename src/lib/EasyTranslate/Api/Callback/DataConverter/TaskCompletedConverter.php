<?php

namespace EasyTranslate\Api\Callback\DataConverter;

use EasyTranslate\Api\Response\TaskCompletedResponse;

class TaskCompletedConverter
{
    /**
     * @param mixed[] $data
     * @return \EasyTranslate\Api\Response\TaskCompletedResponse
     */
    public function convert($data)
    {
        return new TaskCompletedResponse($data);
    }
}
