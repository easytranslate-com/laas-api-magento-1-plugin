<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Content_Importer_Product
    extends EasyTranslate_Connector_Model_Content_Importer_AbstractImporter
{
    protected function _importObject(string $id, array $attributes, int $sourceStoreId, int $targetStoreId): void
    {
        $skus = Mage::getResourceModel('catalog/product')->getProductsSku([$id]);
        if (empty($skus)) {
            // entity has been deleted in the meantime, do nothing
            return;
        }
        Mage::getModel('catalog/product_action')->updateAttributes([$id], $attributes, $targetStoreId);
    }
}
