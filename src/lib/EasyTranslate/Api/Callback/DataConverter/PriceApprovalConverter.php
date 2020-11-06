<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Callback\DataConverter;

use EasyTranslate\Api\Response\PriceApprovalResponse;

class PriceApprovalConverter
{
    public function convert(array $data): PriceApprovalResponse
    {
        return new PriceApprovalResponse($data);
    }
}
