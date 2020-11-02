<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Response;

interface ResponseInterface
{
    /**
     * @return array raw data from the API response
     */
    public function getData(): array;

    public function mapFields(array $data): void;
}
