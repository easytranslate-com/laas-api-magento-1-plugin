<?php

class EasyTranslate_Connector_Model_Content_Importer_CmsBlock
    extends EasyTranslate_Connector_Model_Content_Importer_AbstractCmsImporter
{
    /**
     * @return void
     * @param string $id
     * @param int $sourceStoreId
     * @param int $targetStoreId
     */
    protected function _importObject($id, array $attributes, $sourceStoreId, $targetStoreId)
    {
        $id = (string) $id;
        $sourceStoreId = (int) $sourceStoreId;
        $targetStoreId = (int) $targetStoreId;
        $block = $this->_loadBaseBlock($id, $sourceStoreId, $targetStoreId);
        if ($block->isObjectNew()) {
            // entity has been deleted in the meantime, do nothing
            return;
        }
        $storeIds = (array)$block->getData('stores');
        if (in_array(Mage_Core_Model_App::ADMIN_STORE_ID, $storeIds, false) && count($storeIds) === 1) {
            $this->_handleExistingGlobalBlock($block, $attributes, $targetStoreId);
        } elseif (in_array($targetStoreId, $storeIds, false) && count($storeIds) === 1) {
            $this->_handleExistingUniqueBlock($block, $attributes);
        } elseif (in_array($targetStoreId, $storeIds, false) && count($storeIds) > 1) {
            $this->_handleExistingBlockWithMultipleStores($block, $attributes, $targetStoreId);
        } else {
            // this should rarely happen - only if the block from the source store has been deleted in the meantime
            $block->setIdentifier($id);
            $this->_handleNonExistingBlock($block, $attributes, $targetStoreId);
        }
    }

    /**
     * @param string $id
     * @param int $sourceStoreId
     * @param int $targetStoreId
     * @return \Mage_Cms_Model_Block
     */
    protected function _loadBaseBlock($id, $sourceStoreId, $targetStoreId)
    {
        $id = (string) $id;
        $sourceStoreId = (int) $sourceStoreId;
        $targetStoreId = (int) $targetStoreId;
        $blockFromTargetStore = $this->_loadExistingBlock($id, $targetStoreId);
        if ($blockFromTargetStore->getId()) {
            // if there is already a block in the target store, use it as a base
            return $blockFromTargetStore;
        }

        // otherwise, use the block from the source store as a base
        return $this->_loadExistingBlock($id, $sourceStoreId);
    }

    /**
     * @param string $id
     * @param int $storeId
     * @return \Mage_Cms_Model_Block
     */
    protected function _loadExistingBlock($id, $storeId)
    {
        $id = (string) $id;
        $storeId = (int) $storeId;
        $block = Mage::getModel('cms/block');
        $block->setData('store_id', $storeId);
        $block->load($id, 'identifier');

        return $block;
    }

    /**
     * @return void
     * @param int $targetStoreId
     */
    protected function _handleExistingGlobalBlock(
        Mage_Cms_Model_Block $block,
        array $newData,
        $targetStoreId
    ) {
        $targetStoreId = (int) $targetStoreId;
        $this->_createNewBlockForStore($block, $newData, $targetStoreId);
    }

    /**
     * @return void
     */
    protected function _handleExistingUniqueBlock(Mage_Cms_Model_Block $block, array $newData)
    {
        $block->addData($newData);
        $this->_objects[] = $block;
    }

    /**
     * @return void
     * @param int $targetStoreId
     */
    protected function _handleExistingBlockWithMultipleStores(
        Mage_Cms_Model_Block $block,
        array $newData,
        $targetStoreId
    ) {
        $targetStoreId = (int) $targetStoreId;
        // first remove the current store ID from the existing CMS block, because blocks must be unique per store
        $storeIds    = (array)$block->getData('stores');
        $newStoreIds = array_diff($storeIds, [$targetStoreId]);
        $block->setData('store_id', $newStoreIds);
        $block->setData('stores', $newStoreIds);
        // save this block directly for subsequent updates
        $block->save();

        $this->_createNewBlockForStore($block, $newData, $targetStoreId);
    }

    /**
     * @return void
     * @param int $targetStoreId
     */
    protected function _handleNonExistingBlock(Mage_Cms_Model_Block $block, array $newData, $targetStoreId)
    {
        $targetStoreId = (int) $targetStoreId;
        $this->_createNewBlockForStore($block, $newData, $targetStoreId);
    }

    /**
     * @return void
     * @param int $targetStoreId
     */
    protected function _createNewBlockForStore(
        Mage_Cms_Model_Block $baseBlock,
        array $newData,
        $targetStoreId
    ) {
        $targetStoreId = (int) $targetStoreId;
        $newBlock = Mage::getModel('cms/block');
        $newBlock->addData($baseBlock->getData());
        $newBlock->addData($newData);
        // make sure that a new block is created!
        $newBlock->unsetData('block_id');
        $newBlock->unsetData('creation_time');
        $newBlock->setData('store_id', [$targetStoreId]);
        $newBlock->setData('stores', [$targetStoreId]);
        $this->_objects[] = $newBlock;
    }
}
