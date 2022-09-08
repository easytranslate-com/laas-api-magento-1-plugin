<?php

namespace EasyTranslate\Api\Response;

interface ResponseInterface
{
    /**
     * @return array raw data from the API response
     */
    public function getData();

    /**
     * @param mixed[] $data
     * @return void
     */
    public function mapFields($data);
}
