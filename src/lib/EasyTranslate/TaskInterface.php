<?php

declare(strict_types=1);

namespace EasyTranslate;

interface TaskInterface
{
    public function getId(): string;

    public function getProjectId(): string;

    public function getTargetContent(): ?string;

    public function getTargetLanguage(): string;
}
