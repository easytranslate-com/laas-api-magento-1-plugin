<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Response;

class CreateProjectResponse extends AbstractResponse
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $currency;

    public function mapFields(array $data): void
    {
        // TODO probably we should return a ProjectInterface here
        if (isset($data['data']['type'], $data['data']['id']) && $data['data']['type'] === 'project') {
            $this->id = $data['data']['id'];
            if (isset($data['data']['attributes']['price'])) {
                $this->price    = (float)$data['data']['attributes']['price']['amount'];
                $this->currency = $data['data']['attributes']['price']['currency'];
            }
        }
        parent::mapFields($data);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }
}
