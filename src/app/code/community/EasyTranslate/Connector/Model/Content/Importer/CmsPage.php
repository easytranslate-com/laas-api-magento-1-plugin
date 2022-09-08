<?php

class EasyTranslate_Connector_Model_Content_Importer_CmsPage
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
        $page = $this->_loadBasePage($id, $sourceStoreId, $targetStoreId);
        if ($page->isObjectNew()) {
            // entity has been deleted in the meantime, do nothing
            return;
        }
        $storeIds = (array)$page->getData('store_id');
        if (in_array(Mage_Core_Model_App::ADMIN_STORE_ID, $storeIds, false) && count($storeIds) === 1) {
            $this->_handleExistingGlobalPage($page, $attributes, $targetStoreId);
        } elseif (in_array($targetStoreId, $storeIds, false) && count($storeIds) === 1) {
            $this->_handleExistingUniquePage($page, $attributes);
        } elseif (in_array($targetStoreId, $storeIds, false) && count($storeIds) > 1) {
            $this->_handleExistingPageWithMultipleStores($page, $attributes, $targetStoreId);
        } else {
            // this should rarely happen - only if the page from the source store has been deleted in the meantime
            $page->setIdentifier($id);
            $this->_handleNonExistingPage($page, $attributes, $targetStoreId);
        }
    }

    /**
     * @param string $id
     * @param int $sourceStoreId
     * @param int $targetStoreId
     * @return \Mage_Cms_Model_Page
     */
    protected function _loadBasePage($id, $sourceStoreId, $targetStoreId)
    {
        $id = (string) $id;
        $sourceStoreId = (int) $sourceStoreId;
        $targetStoreId = (int) $targetStoreId;
        $pageFromTargetStore = $this->_loadExistingPage($id, $targetStoreId);
        if ($pageFromTargetStore->getId()) {
            // if there is already a page in the target store, use it as a base
            return $pageFromTargetStore;
        }

        // otherwise, use the page from the source store as a base
        return $this->_loadExistingPage($id, $sourceStoreId);
    }

    /**
     * @param string $id
     * @param int $storeId
     * @return \Mage_Cms_Model_Page
     */
    protected function _loadExistingPage($id, $storeId)
    {
        $id = (string) $id;
        $storeId = (int) $storeId;
        $page = Mage::getModel('cms/page');
        $page->setData('store_id', $storeId);
        $page->load($id, 'identifier');

        return $page;
    }

    /**
     * @return void
     * @param int $targetStoreId
     */
    protected function _handleExistingGlobalPage(
        Mage_Cms_Model_Page $page,
        array $newData,
        $targetStoreId
    ) {
        $targetStoreId = (int) $targetStoreId;
        $this->_createNewPageForStore($page, $newData, $targetStoreId);
    }

    /**
     * @return void
     */
    protected function _handleExistingUniquePage(Mage_Cms_Model_Page $page, array $newData)
    {
        $page->addData($newData);
        // workaround for a Magento bug - stores are not set in _afterLoad, but checked in _beforeSave / getIsUniquePageToStores
        $page->setData('stores', $page->getData('store_id'));
        $this->_objects[] = $page;
    }

    /**
     * @return void
     * @param int $targetStoreId
     */
    protected function _handleExistingPageWithMultipleStores(
        Mage_Cms_Model_Page $page,
        array $newData,
        $targetStoreId
    ) {
        $targetStoreId = (int) $targetStoreId;
        // first remove the current store ID from the existing CMS page, because pages must be unique per store
        $storeIds    = (array)$page->getData('store_id');
        $newStoreIds = array_diff($storeIds, [$targetStoreId]);
        $page->setData('store_id', $newStoreIds);
        $page->setData('stores', $newStoreIds);
        // save this page directly for subsequent updates
        $page->save();

        $this->_createNewPageForStore($page, $newData, $targetStoreId);
    }

    /**
     * @return void
     * @param int $targetStoreId
     */
    protected function _handleNonExistingPage(Mage_Cms_Model_Page $page, array $newData, $targetStoreId)
    {
        $targetStoreId = (int) $targetStoreId;
        $this->_createNewPageForStore($page, $newData, $targetStoreId);
    }

    /**
     * @return void
     * @param int $targetStoreId
     */
    protected function _createNewPageForStore(
        Mage_Cms_Model_Page $basePage,
        array $newData,
        $targetStoreId
    ) {
        $targetStoreId = (int) $targetStoreId;
        $newPage = Mage::getModel('cms/page');
        $newPage->addData($basePage->getData());
        $newPage->addData($newData);
        // make sure that a new page is created!
        $newPage->unsetData('page_id');
        $newPage->unsetData('creation_time');
        $newPage->setData('store_id', [$targetStoreId]);
        $newPage->setData('stores', [$targetStoreId]);
        $this->_objects[] = $newPage;
    }
}
