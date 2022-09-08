<?php

class EasyTranslate_Connector_Model_Resource_Cms_Block_Collection extends Mage_Cms_Model_Resource_Block_Collection
{
    /**
     * @return \Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();

        // this ensures that the count is correct even with the joins we make
        $countSelect->reset(Zend_Db_Select::COLUMNS);
        $countSelect->columns('COUNT(DISTINCT main_table.block_id)');

        return $countSelect;
    }
}
