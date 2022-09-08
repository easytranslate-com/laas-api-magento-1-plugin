<?php

namespace EasyTranslate\Api\Request;

interface RequestInterface
{
    const TYPE_GET = 'GET';
    const TYPE_POST = 'POST';
    const TYPE_PUT = 'PUT';

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getResource();

    /**
     * @return mixed[]
     */
    public function getData();

    /**
     * @return bool
     */
    public function requiresAuthentication();
}
