<?php

class EasyTranslate_Connector_Model_Content_Importer_Product
    extends EasyTranslate_Connector_Model_Content_Importer_AbstractImporter
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
        $skus = Mage::getResourceModel('catalog/product')->getProductsSku([$id]);
        if (empty($skus)) {
            // entity has been deleted in the meantime, do nothing
            return;
        }
        Mage::getModel('catalog/product_action')->updateAttributes([$id], $attributes, $targetStoreId);
    }
}
