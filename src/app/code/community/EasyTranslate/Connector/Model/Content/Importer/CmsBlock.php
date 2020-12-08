<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Content_Importer_CmsBlock
    extends EasyTranslate_Connector_Model_Content_Importer_AbstractCmsImporter
{
    protected function _importObject(string $id, array $attributes, int $sourceStoreId, int $targetStoreId): void
    {
        $block    = $this->_loadBaseBlock($id, $sourceStoreId, $targetStoreId);
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

    protected function _loadBaseBlock(string $id, int $sourceStoreId, int $targetStoreId): Mage_Cms_Model_Block
    {
        $blockFromTargetStore = $this->_loadExistingBlock($id, $targetStoreId);
        if ($blockFromTargetStore->getId()) {
            // if there is already a block in the target store, use it as a base
            return $blockFromTargetStore;
        }

        // otherwise, use the block from the source store as a base
        return $this->_loadExistingBlock($id, $sourceStoreId);
    }

    protected function _loadExistingBlock(string $id, int $storeId): Mage_Cms_Model_Block
    {
        $block = Mage::getModel('cms/block');
        $block->setData('store_id', $storeId);
        $block->load($id, 'identifier');

        return $block;
    }

    protected function _handleExistingGlobalBlock(
        Mage_Cms_Model_Block $block,
        array $newData,
        int $targetStoreId
    ): void {
        $this->_createNewBlockForStore($block, $newData, $targetStoreId);
    }

    protected function _handleExistingUniqueBlock(Mage_Cms_Model_Block $block, array $newData): void
    {
        $block->addData($newData);
        $this->_objects[] = $block;
    }

    protected function _handleExistingBlockWithMultipleStores(
        Mage_Cms_Model_Block $block,
        array $newData,
        int $targetStoreId
    ): void {
        // first remove the current store ID from the existing CMS block, because blocks must be unique per store
        $storeIds    = (array)$block->getData('stores');
        $newStoreIds = array_diff($storeIds, [$targetStoreId]);
        $block->setData('store_id', $newStoreIds);
        $block->setData('stores', $newStoreIds);
        // save this block directly for subsequent updates
        $block->save();

        $this->_createNewBlockForStore($block, $newData, $targetStoreId);
    }

    protected function _handleNonExistingBlock(Mage_Cms_Model_Block $block, array $newData, int $targetStoreId): void
    {
        $this->_createNewBlockForStore($block, $newData, $targetStoreId);
    }

    protected function _createNewBlockForStore(
        Mage_Cms_Model_Block $baseBlock,
        array $newData,
        int $targetStoreId
    ): void {
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
