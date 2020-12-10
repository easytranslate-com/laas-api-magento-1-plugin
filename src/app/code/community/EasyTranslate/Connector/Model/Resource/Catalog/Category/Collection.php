<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Resource_Catalog_Category_Collection
    extends Mage_Catalog_Model_Resource_Category_Collection
{
    public function getSelectCountSql(): Varien_Db_Select
    {
        $countSelect = parent::getSelectCountSql();

        // this ensures that the count is correct even with the joins we make
        $countSelect->reset(Zend_Db_Select::GROUP);
        $countSelect->reset(Zend_Db_Select::COLUMNS);
        $countSelect->columns('COUNT(DISTINCT e.entity_id)');

        return $countSelect;
    }
}
