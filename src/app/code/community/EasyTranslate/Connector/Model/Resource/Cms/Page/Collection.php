<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Resource_Cms_Page_Collection extends Mage_Cms_Model_Resource_Page_Collection
{
    public function getSelectCountSql(): Varien_Db_Select
    {
        $countSelect = parent::getSelectCountSql();

        // this ensures that the count is correct even with the joins we make
        $countSelect->reset(Zend_Db_Select::COLUMNS);
        $countSelect->columns('COUNT(DISTINCT main_table.page_id)');

        return $countSelect;
    }
}
