<?php

class EasyTranslate_Connector_Model_Content_Generator_CmsPage
    extends EasyTranslate_Connector_Model_Content_Generator_AbstractGenerator
{
    const ENTITY_CODE = 'cms_page';

    /**
     * @var string
     */
    protected $_idField = 'identifier';

    public function __construct()
    {
        parent::__construct();
        $this->_attributeCodes = $this->_config->getCmsPagesAttributes();
    }

    /**
     * @param int $storeId
     * @return \Varien_Data_Collection_Db
     */
    protected function _getCollection(array $modelIds, $storeId)
    {
        $storeId = (int) $storeId;
        // re-load CMS pages based on identifiers (a language-specific one may have been added after project creation)
        $identifiers = Mage::getModel('cms/page')
            ->getCollection()
            ->addFieldToFilter('page_id', ['in' => $modelIds])
            ->getColumnValues($this->_idField);
        $cmsBlocks   = Mage::getModel('cms/page')
            ->getCollection()
            ->addFieldToSelect($this->_attributeCodes)
            ->addFieldToSelect($this->_idField)
            ->addStoreFilter($storeId)
            ->addFieldToFilter($this->_idField, ['in' => $identifiers]);

        return Mage::getModel('easytranslate/content_generator_filter_cms')
            ->filterEntities($cmsBlocks, $identifiers);
    }
}
