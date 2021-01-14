<?php

declare(strict_types=1);

namespace EasyTranslate;

interface ProjectInterface
{
    public function getId(): string;

    public function getTeam(): string;

    public function getSourceLanguage(): string;

    public function getTargetLanguages(): array;

    public function getCallbackUrl(): ?string;

    public function getContent(): ?array;

    public function getWorkflow(): string;

    public function getFolderId(): ?string;

    public function getFolderName(): ?string;

    public function getName(): ?string;

    public function getTasks(): array;

    public function getPrice(): ?float;

    public function getCurrency(): ?string;
}
