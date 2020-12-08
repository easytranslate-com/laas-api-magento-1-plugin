<?php

declare(strict_types=1);

namespace EasyTranslate;

class Task implements TaskInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var ProjectInterface
     */
    private $project;

    /**
     * @var string
     */
    private $targetContent;

    /**
     * @var string
     */
    private $targetLanguage;

    public function getId(): string
    {
        return $this->id;
    }

    public function getProject(): ProjectInterface
    {
        return $this->project;
    }

    public function getTargetContent(): ?string
    {
        return $this->targetContent;
    }

    public function getTargetLanguage(): string
    {
        return $this->targetLanguage;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setProject(ProjectInterface $project): void
    {
        $this->project = $project;
    }

    public function setTargetContent(string $targetContent): void
    {
        $this->targetContent = $targetContent;
    }

    public function setTargetLanguage(string $targetLanguage): void
    {
        $this->targetLanguage = $targetLanguage;
    }
}
