<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Content_Importer_CmsBlock
    extends EasyTranslate_Connector_Model_Content_Importer_AbstractCmsImporter
{
    protected function _importObject(string $id, array $attributes, int $sourceStoreId, array $targetStoreIds): void
    {
        foreach ($targetStoreIds as $targetStoreId) {
            $block    = $this->_loadExistingBlock($id, $sourceStoreId);
            $storeIds = (array)$block->getData('stores');
            if (in_array($targetStoreId, $storeIds, false) && count($storeIds) === 1) {
                $this->_handleExistingUniqueBlock($block, $attributes);
            } elseif (in_array($targetStoreId, $storeIds, false) && count($storeIds) > 1) {
                $this->_handleExistingBlockWithMultipleStores($block, $attributes, (int)$targetStoreId);
            } else {
                // this should rarely happen - only if the block from the source store has been deleted in the meantime
                $this->_handleNonExistingBlock($block, $attributes, $targetStoreId);
            }
        }
    }

    protected function _loadExistingBlock(string $id, int $sourceStoreId): Mage_Cms_Model_Block
    {
        $block = Mage::getModel('cms/block');
        $block->setData('store_id', $sourceStoreId);
        $block->load($id);

        return $block;
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
        $block->setData('stores', $newStoreIds);
        // save this block directly for subsequent updates
        $block->save();

        $newBlock = Mage::getModel('cms/block');
        $newBlock->addData($block->getData());
        $block->unsetData('block_id');
        $block->addData($newData);
        $this->_objects[] = $block;
    }

    protected function _handleNonExistingBlock(Mage_Cms_Model_Block $block, array $newData, int $targetStoreId): void
    {
        $block->addData($newData);
        $block->setData('stores', [$targetStoreId]);
        $this->_objects[] = $block;
    }
}
