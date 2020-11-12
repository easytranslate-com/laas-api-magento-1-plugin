<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Task_Queue extends Mage_Core_Model_Abstract
{
    protected function _construct(): void
    {
        $this->_init('easytranslate/task_queue');
    }
}
