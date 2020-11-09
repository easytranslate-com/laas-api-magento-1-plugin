<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Response;

class PriceApprovalResponse extends AbstractResponse
{
    /**
     * @var string
     */
    private $id = '';

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
        if (isset($data['data']['type'], $data['data']['id']) && $data['data']['type'] === 'project') {
            $this->id = $data['data']['id'];
            if (isset($data['data']['attributes']['price'])) {
                $priceInCents   = (int)$data['data']['attributes']['price']['amount'];
                $this->price    = (float)$priceInCents / 100;
                $this->currency = $data['data']['attributes']['price']['currency'];
            }
        }
        parent::mapFields($data);
    }

    public function getId(): string
    {
        return $this->id;
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
