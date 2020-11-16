<?php

declare(strict_types=1);

use EasyTranslate\TaskInterface;

class EasyTranslate_Connector_Model_Bridge_Task implements TaskInterface
{
    /**
     * @var EasyTranslate_Connector_Model_Task_Queue
     */
    protected $_magentoTask;

    public function __construct(EasyTranslate_Connector_Model_Task_Queue $task)
    {
        $this->_magentoTask = $task;
    }

    public function getId(): string
    {
        return $this->_magentoTask->getData('external_id');
    }

    public function getProjectId(): string
    {
        return $this->_magentoTask->getProject()->getData('external_id');
    }

    public function getTargetContent(): ?string
    {
        return $this->_magentoTask->getData('content_link');
    }

    public function getTargetLanguage(): string
    {
        $targetStore  = $this->_magentoTask->getData('store_id');
        $targetLocale = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE, $targetStore);

        return Mage::getModel('easytranslate/locale_targetMapper')->mapMagentoCodeToExternalCode($targetLocale);
    }
}
