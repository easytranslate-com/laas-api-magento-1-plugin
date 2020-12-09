<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Response;

use EasyTranslate\Api\ApiException;

class PriceApprovalResponse extends AbstractResponse
{
    /**
     * @var string
     */
    private $projectId = '';

    /**
     * @var float
     */
    private $price = 0.0;

    /**
     * @var string
     */
    private $currency = '';

    public function mapFields(array $data): void
    {
        parent::mapFields($data);
        if (!isset($data['data']['type'], $data['data']['id']) || $data['data']['type'] !== 'project') {
            throw new ApiException(sprintf('Invalid response data in response class %s', self::class));
        }
        $this->projectId = $data['data']['id'];
        if (isset($data['data']['attributes']['price'])) {
            $priceInCents   = (int)$data['data']['attributes']['price']['amount'];
            $this->price    = (float)$priceInCents / 100;
            $this->currency = $data['data']['attributes']['price']['currency'];
        }
    }

    public function getProjectId(): string
    {
        return $this->projectId;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
