<?php

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

    /**
     * @return mixed[]
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed[] $data
     * @return void
     */
    public function mapFields($data)
    {
        $this->data = $data;
    }
}
