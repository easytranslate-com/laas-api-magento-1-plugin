<?php

declare(strict_types=1);

namespace EasyTranslate;

interface TaskInterface
{
    public function getId(): string;

    public function getProject(): ProjectInterface;

    public function getTargetContent(): ?string;

    public function getTargetLanguage(): string;
}
