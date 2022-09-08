<?php

use EasyTranslate\ProjectInterface;
use EasyTranslate\TaskInterface;

class EasyTranslate_Connector_Model_Bridge_Task implements TaskInterface
{
    /**
     * @var EasyTranslate_Connector_Model_Task
     */
    protected $_magentoTask;

    public function __construct(EasyTranslate_Connector_Model_Task $task)
    {
        $this->_magentoTask = $task;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_magentoTask->getData('external_id');
    }

    /**
     * @return \EasyTranslate\ProjectInterface
     */
    public function getProject()
    {
        $magentoProject = $this->_magentoTask->getProject();

        return Mage::getModel('easytranslate/bridge_project', $magentoProject);
    }

    /**
     * @return string|null
     */
    public function getTargetContent()
    {
        return $this->_magentoTask->getData('content_link');
    }

    /**
     * @return string
     */
    public function getTargetLanguage()
    {
        $targetStore  = $this->_magentoTask->getData('store_id');
        $targetLocale = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE, $targetStore);

        return Mage::getModel('easytranslate/locale_targetMapper')->mapMagentoCodeToExternalCode($targetLocale);
    }
}
