<?php

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

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \EasyTranslate\ProjectInterface
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return string|null
     */
    public function getTargetContent()
    {
        return $this->targetContent;
    }

    /**
     * @return string
     */
    public function getTargetLanguage()
    {
        return $this->targetLanguage;
    }

    /**
     * @param string $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param \EasyTranslate\ProjectInterface $project
     * @return void
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @param string $targetContent
     * @return void
     */
    public function setTargetContent($targetContent)
    {
        $this->targetContent = $targetContent;
    }

    /**
     * @param string $targetLanguage
     * @return void
     */
    public function setTargetLanguage($targetLanguage)
    {
        $this->targetLanguage = $targetLanguage;
    }
}
