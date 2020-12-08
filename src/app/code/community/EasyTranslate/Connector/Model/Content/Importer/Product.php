<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Content_Importer_Product
    extends EasyTranslate_Connector_Model_Content_Importer_AbstractImporter
{
    protected function _importObject(string $id, array $attributes, int $sourceStoreId, int $targetStoreId): void
    {
        Mage::getModel('catalog/product_action')->updateAttributes([$id], $attributes, $targetStoreId);
    }
}
