<?php

declare(strict_types=1);

namespace EasyTranslate;

interface ProjectInterface
{
    public function getTeam(): string;

    public function getSourceLanguage(): string;

    public function getTargetLanguages(): array;

    public function getCallbackUrl(): string;

    public function getContent(): array;

    public function getFolderId(): ?string;

    public function getFolderName(): ?string;

    public function getName(): ?string;
}
