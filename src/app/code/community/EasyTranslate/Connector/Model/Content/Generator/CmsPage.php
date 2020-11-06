<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Content_Generator_CmsPage
    extends EasyTranslate_Connector_Model_Content_Generator_AbstractGenerator
{
    public const ENTITY_CODE = 'cms_page';

    public function __construct()
    {
        parent::__construct();
        $this->_attributeCodes = $this->_config->getCmsPagesAttributes();
    }

    protected function _getCollection(array $modelIds): Varien_Data_Collection_Db
    {
        return Mage::getModel('cms/page')
            ->getCollection()
            ->addFieldToSelect($this->_attributeCodes)
            ->addFieldToFilter('page_id', ['in' => $modelIds]);
    }
}
