<?php

namespace EasyTranslate;

interface ProjectInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getTeam();

    /**
     * @return string
     */
    public function getSourceLanguage();

    /**
     * @return mixed[]
     */
    public function getTargetLanguages();

    /**
     * @return string|null
     */
    public function getCallbackUrl();

    /**
     * @return mixed[]|null
     */
    public function getContent();

    /**
     * @return string
     */
    public function getWorkflow();

    /**
     * @return string|null
     */
    public function getFolderId();

    /**
     * @return string|null
     */
    public function getFolderName();

    /**
     * @return string|null
     */
    public function getName();

    /**
     * @return mixed[]
     */
    public function getTasks();

    /**
     * @return float|null
     */
    public function getPrice();

    /**
     * @return string|null
     */
    public function getCurrency();
}
