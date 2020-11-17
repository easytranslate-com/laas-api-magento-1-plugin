<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Content_Importer_CmsPage
    extends EasyTranslate_Connector_Model_Content_Importer_AbstractImporter
{
    protected function _createObjects(string $id, array $attributes, int $sourceStoreId, array $targetStoreIds): void
    {
        foreach ($targetStoreIds as $targetStoreId) {
            $page     = $this->_loadExistingPage($id, $sourceStoreId);
            $storeIds = (array)$page->getData('store_id');
            if (in_array($targetStoreId, $storeIds, false) && count($storeIds) === 1) {
                $this->_handleExistingUniquePage($page, $attributes);
            } elseif (in_array($targetStoreId, $storeIds, false) && count($storeIds) > 1) {
                $this->_handleExistingNonUniquePage($page, $attributes, (int)$targetStoreId);
            } else {
                // this should rarely happen - only if the page from the source store has been deleted in the meantime
                $this->_handleNonExistingPage($page, $attributes, $targetStoreId);
            }
        }
    }

    protected function _loadExistingPage(string $id, int $sourceStoreId): Mage_Cms_Model_Page
    {
        $page = Mage::getModel('cms/page');
        $page->setData('store_id', $sourceStoreId);
        $page->load($id);

        return $page;
    }

    protected function _handleExistingUniquePage(Mage_Cms_Model_Page $page, array $newData): void
    {
        $page->addData($newData);
        $this->_objects[] = $page;
    }

    protected function _handleExistingNonUniquePage(
        Mage_Cms_Model_Page $page,
        array $newData,
        int $targetStoreId
    ): void {
        // first remove the current store ID from the existing CMS page
        $storeIds    = (array)$page->getData('store_id');
        $newStoreIds = array_diff($storeIds, [$targetStoreId]);
        $page->setData('store_id', $newStoreIds);
        // save this page directly for subsequent updates
        $page->save();

        $newPage = Mage::getModel('cms/page');
        $newPage->addData($page->getData());
        $page->unsetData('page_id');
        $page->addData($newData);
        $this->_objects[] = $page;
    }

    protected function _handleNonExistingPage(Mage_Cms_Model_Page $page, array $newData, int $targetStoreId): void
    {
        $page->addData($newData);
        $page->setData('store_id', [$targetStoreId]);
        $this->_objects[] = $page;
    }
}
