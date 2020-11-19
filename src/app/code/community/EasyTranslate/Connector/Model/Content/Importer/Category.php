<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Content_Importer_Category
    extends EasyTranslate_Connector_Model_Content_Importer_AbstractImporter
{
    protected function _importObject(string $id, array $attributes, int $sourceStoreId, array $targetStoreIds): void
    {
        foreach ($targetStoreIds as $targetStoreId) {
            $category = Mage::getModel('catalog/category')->setStoreId($targetStoreId)->load($id);
            $category->addData($attributes);
            /** @var Mage_Catalog_Model_Resource_Category $categoryResource */
            $categoryResource = $category->getResource();
            foreach ($attributes as $attributeCode => $attributeValue) {
                $categoryResource->saveAttribute($category, $attributeCode);
            }
        }
    }
}
