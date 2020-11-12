<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Resource_Task_Queue extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct(): void
    {
        $this->_init('easytranslate/task_queue', 'task_id');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $task): EasyTranslate_Connector_Model_Resource_Task_Queue
    {
        if (!$task->getId()) {
            $task->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
        }

        return parent::_beforeSave($task);
    }
}
