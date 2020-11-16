<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Content_Importer_CmsBlock
    extends EasyTranslate_Connector_Model_Content_Importer_AbstractImporter
{
    protected function _createObjects(string $id, array $attributes, array $storeIds): void
    {
        foreach ($storeIds as $storeId) {
            $cmsBlock = Mage::getModel('cms/block');
            $cmsBlock->setData('store_id', $storeId);
            // this ensures that existing blocks are replaced
            $cmsBlock->load($id);
            $cmsBlock->addData($attributes);
            $this->_objects[] = $cmsBlock;
        }
    }
}
