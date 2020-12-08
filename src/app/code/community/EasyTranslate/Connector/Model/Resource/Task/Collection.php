<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Resource_Task_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct(): void
    {
        $this->_init('easytranslate/task');
    }
}
