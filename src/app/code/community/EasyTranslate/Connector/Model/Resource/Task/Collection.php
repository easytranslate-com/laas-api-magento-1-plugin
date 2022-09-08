<?php

class EasyTranslate_Connector_Model_Resource_Task_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('easytranslate/task');
    }
}
