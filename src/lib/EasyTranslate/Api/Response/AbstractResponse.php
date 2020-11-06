<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Response;

abstract class AbstractResponse implements ResponseInterface
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->mapFields($data);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function mapFields(array $data): void
    {
        $this->data = $data;
    }
}
