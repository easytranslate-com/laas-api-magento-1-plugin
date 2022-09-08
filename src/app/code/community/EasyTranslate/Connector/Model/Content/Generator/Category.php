<?php

class EasyTranslate_Connector_Model_Content_Generator_Category
    extends EasyTranslate_Connector_Model_Content_Generator_AbstractEavGenerator
{
    const ENTITY_CODE = 'catalog_category';

    public function __construct()
    {
        parent::__construct();
        $this->_attributeCodes = $this->_config->getCategoriesAttributes();
    }

    /**
     * @param int $storeId
     * @return \Varien_Data_Collection_Db
     */
    protected function _getCollection(array $modelIds, $storeId)
    {
        $storeId = (int) $storeId;
        return Mage::getModel('catalog/category')
            ->getCollection()
            ->setStoreId($storeId)
            ->addAttributeToSelect($this->_attributeCodes)
            ->addAttributeToFilter('entity_id', ['in' => $modelIds]);
    }
}
