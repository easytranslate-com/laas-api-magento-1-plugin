<?php

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

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @return string
     */
    public function getSourceLanguage()
    {
        return $this->sourceLanguage;
    }

    /**
     * @return mixed[]
     */
    public function getTargetLanguages()
    {
        return $this->targetLanguages;
    }

    /**
     * @return string|null
     */
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }

    /**
     * @return mixed[]|null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }

    /**
     * @return string|null
     */
    public function getFolderId()
    {
        return $this->folderId;
    }

    /**
     * @return string|null
     */
    public function getFolderName()
    {
        return $this->folderName;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @return float|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string|null
     */
    public function getCurrency()
    {
        return $this->currency;
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
     * @param string $team
     * @return void
     */
    public function setTeam($team)
    {
        $this->team = $team;
    }

    /**
     * @param string $sourceLanguage
     * @return void
     */
    public function setSourceLanguage($sourceLanguage)
    {
        $this->sourceLanguage = $sourceLanguage;
    }

    /**
     * @param mixed[] $targetLanguages
     * @return void
     */
    public function setTargetLanguages($targetLanguages)
    {
        $this->targetLanguages = $targetLanguages;
    }

    /**
     * @param string|null $callbackUrl
     * @return void
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
    }

    /**
     * @param mixed[]|null $content
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param string $workflow
     * @return void
     */
    public function setWorkflow($workflow)
    {
        $this->workflow = $workflow;
    }

    /**
     * @param string|null $folderId
     * @return void
     */
    public function setFolderId($folderId)
    {
        $this->folderId = $folderId;
    }

    /**
     * @param string|null $folderName
     * @return void
     */
    public function setFolderName($folderName)
    {
        $this->folderName = $folderName;
    }

    /**
     * @param string|null $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed[] $tasks
     * @return void
     */
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * @param float|null $price
     * @return void
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @param string|null $currency
     * @return void
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
}
