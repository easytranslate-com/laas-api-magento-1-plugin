<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Content_Generator_CmsBlock
    extends EasyTranslate_Connector_Model_Content_Generator_AbstractGenerator
{
    public const ENTITY_CODE = 'cms_block';

    /**
     * @var string
     */
    protected $_idField = 'identifier';

    public function __construct()
    {
        parent::__construct();
        $this->_attributeCodes = $this->_config->getCmsBlocksAttributes();
    }

    protected function _getCollection(array $modelIds, int $storeId): Varien_Data_Collection_Db
    {
        // re-load CMS blocks based on identifiers (a language-specific one may have been added after project creation)
        $identifiers = Mage::getModel('cms/block')
            ->getCollection()
            ->addFieldToFilter('block_id', ['in' => $modelIds])
            ->getColumnValues($this->_idField);
        $cmsBlocks   = Mage::getModel('cms/block')
            ->getCollection()
            ->addFieldToSelect($this->_attributeCodes)
            ->addFieldToSelect($this->_idField)
            ->addStoreFilter($storeId)
            ->addFieldToFilter($this->_idField, ['in' => $identifiers]);

        return Mage::getModel('easytranslate/content_generator_filter_cms')
            ->filterEntities($cmsBlocks, $identifiers);
    }
}
