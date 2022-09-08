<?php

namespace EasyTranslate\Api\Request;

abstract class AbstractRequest implements RequestInterface
{
    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_GET;
    }

    /**
     * @return mixed[]
     */
    public function getData()
    {
        return [];
    }

    /**
     * @return bool
     */
    public function requiresAuthentication()
    {
        return true;
    }
}
