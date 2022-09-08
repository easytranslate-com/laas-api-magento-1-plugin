<?php

class EasyTranslate_Connector_Model_Resource_Catalog_Category_Collection
    extends Mage_Catalog_Model_Resource_Category_Collection
{
    /**
     * @return \Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();

        // this ensures that the count is correct even with the joins we make
        $countSelect->reset(Zend_Db_Select::GROUP);
        $countSelect->reset(Zend_Db_Select::COLUMNS);
        $countSelect->columns('COUNT(DISTINCT e.entity_id)');

        return $countSelect;
    }
}
