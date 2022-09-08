<?php

namespace EasyTranslate;

interface TaskInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return \EasyTranslate\ProjectInterface
     */
    public function getProject();

    /**
     * @return string|null
     */
    public function getTargetContent();

    /**
     * @return string
     */
    public function getTargetLanguage();
}
