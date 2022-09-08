<?php

class EasyTranslate_Connector_Model_Resource_Task extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('easytranslate/task', 'task_id');
    }

    /**
     * @return \EasyTranslate_Connector_Model_Resource_Task
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $task)
    {
        if (!$task->getId()) {
            $task->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
        }

        return parent::_beforeSave($task);
    }
}
