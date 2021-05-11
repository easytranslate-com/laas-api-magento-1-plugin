<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Content_Generator_Category
    extends EasyTranslate_Connector_Model_Content_Generator_AbstractGenerator
{
    public const ENTITY_CODE = 'catalog_category';

    public function __construct()
    {
        parent::__construct();
        $this->_attributeCodes = $this->_config->getCategoriesAttributes();
    }

    protected function _getCollection(array $modelIds, int $storeId): Varien_Data_Collection_Db
    {
        return Mage::getModel('catalog/category')
            ->getCollection()
            ->setStoreId($storeId)
            ->addAttributeToSelect($this->_attributeCodes)
            ->addAttributeToFilter('entity_id', ['in' => $modelIds]);
    }
}
