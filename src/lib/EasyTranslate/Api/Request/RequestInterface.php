<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Request;

interface RequestInterface
{
    public const TYPE_GET = 'GET';
    public const TYPE_POST = 'POST';
    public const TYPE_PUT = 'PUT';

    public function getType(): string;

    public function getResource(): string;

    public function getData(): array;

    public function requiresAuthentication(): bool;
}
