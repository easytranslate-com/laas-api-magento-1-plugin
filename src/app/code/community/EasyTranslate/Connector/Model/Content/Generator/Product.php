<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Content_Generator_Product
    extends EasyTranslate_Connector_Model_Content_Generator_AbstractGenerator
{
    public const ENTITY_CODE = 'catalog_product';

    public function __construct()
    {
        parent::__construct();
        $this->_attributeCodes = $this->_config->getProductsAttributes();
    }

    protected function _getCollection(array $modelIds): Varien_Data_Collection_Db
    {
        return Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect($this->_attributeCodes)
            ->addAttributeToFilter('entity_id', ['in' => $modelIds]);
    }
}