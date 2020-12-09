<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Content_Importer_Category
    extends EasyTranslate_Connector_Model_Content_Importer_AbstractImporter
{
    protected function _importObject(string $id, array $attributes, int $sourceStoreId, int $targetStoreId): void
    {
        $category = Mage::getModel('catalog/category')->setStoreId($targetStoreId)->load($id);
        if ($category->isObjectNew()) {
            // entity has been deleted in the meantime, do nothing
            return;
        }
        $category->addData($attributes);
        /** @var Mage_Catalog_Model_Resource_Category $categoryResource */
        $categoryResource = $category->getResource();
        foreach ($attributes as $attributeCode => $attributeValue) {
            // using Mage_Catalog_Model_Category::save unchecks all "Use Default Value" flags
            // hence, save each attribute individually to make sure only these ones are changed
            $categoryResource->saveAttribute($category, $attributeCode);
        }
    }
}
