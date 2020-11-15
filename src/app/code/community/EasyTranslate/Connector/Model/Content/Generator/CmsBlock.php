<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Content_Generator_CmsBlock
    extends EasyTranslate_Connector_Model_Content_Generator_AbstractGenerator
{
    public const ENTITY_CODE = 'cms_block';

    /**
     * @var string
     */
    protected $_idField = 'block_id';

    public function __construct()
    {
        parent::__construct();
        $this->_attributeCodes = $this->_config->getCmsBlocksAttributes();
    }

    protected function _getCollection(array $modelIds): Varien_Data_Collection_Db
    {
        return Mage::getModel('cms/block')
            ->getCollection()
            ->addFieldToSelect($this->_attributeCodes)
            ->addFieldToFilter('block_id', ['in' => $modelIds]);
    }
}
