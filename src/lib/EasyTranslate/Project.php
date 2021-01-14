<?php

declare(strict_types=1);

namespace EasyTranslate;

class Project implements ProjectInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $team;

    /**
     * @var string
     */
    private $sourceLanguage;

    /**
     * @var array
     */
    private $targetLanguages;

    /**
     * @var string
     */
    private $callbackUrl;

    /**
     * @var array
     */
    private $content;

    /**
     * @var string
     */
    private $workflow = Workflow::TYPE_TRANSLATION;

    /**
     * @var string
     */
    private $folderId;

    /**
     * @var string
     */
    private $folderName;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $tasks = [];

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $currency;

    public function getId(): string
    {
        return $this->id;
    }

    public function getTeam(): string
    {
        return $this->team;
    }

    public function getSourceLanguage(): string
    {
        return $this->sourceLanguage;
    }

    public function getTargetLanguages(): array
    {
        return $this->targetLanguages;
    }

    public function getCallbackUrl(): ?string
    {
        return $this->callbackUrl;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function getWorkflow(): string
    {
        return $this->workflow;
    }

    public function getFolderId(): ?string
    {
        return $this->folderId;
    }

    public function getFolderName(): ?string
    {
        return $this->folderName;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setTeam(string $team): void
    {
        $this->team = $team;
    }

    public function setSourceLanguage(string $sourceLanguage): void
    {
        $this->sourceLanguage = $sourceLanguage;
    }

    public function setTargetLanguages(array $targetLanguages): void
    {
        $this->targetLanguages = $targetLanguages;
    }

    public function setCallbackUrl(?string $callbackUrl): void
    {
        $this->callbackUrl = $callbackUrl;
    }

    public function setContent(?array $content): void
    {
        $this->content = $content;
    }

    public function setWorkflow(string $workflow): void
    {
        $this->workflow = $workflow;
    }

    public function setFolderId(?string $folderId): void
    {
        $this->folderId = $folderId;
    }

    public function setFolderName(?string $folderName): void
    {
        $this->folderName = $folderName;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function setTasks(array $tasks): void
    {
        $this->tasks = $tasks;
    }

    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }
}
