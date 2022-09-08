<?php

namespace EasyTranslate\Api\Callback\DataConverter;

use EasyTranslate\Api\Response\PriceApprovalResponse;

class PriceApprovalConverter
{
    /**
     * @param mixed[] $data
     * @return \EasyTranslate\Api\Response\PriceApprovalResponse
     */
    public function convert($data)
    {
        return new PriceApprovalResponse($data);
    }
}
