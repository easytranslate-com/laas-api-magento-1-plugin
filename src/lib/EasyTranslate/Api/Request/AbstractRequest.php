<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Request;

abstract class AbstractRequest implements RequestInterface
{
    public function getType(): string
    {
        return self::TYPE_GET;
    }

    public function getData(): array
    {
        return [];
    }

    public function requiresAuthentication(): bool
    {
        return true;
    }
}
