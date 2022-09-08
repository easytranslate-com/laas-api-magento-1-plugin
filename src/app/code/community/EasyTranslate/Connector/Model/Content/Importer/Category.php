<?php

class EasyTranslate_Connector_Model_Content_Importer_Category
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
        // disable flat, so that we can use saveAttribute (not available in the flat resource model)
        $category = Mage::getModel('catalog/category', ['disable_flat' => true])
            ->setStoreId($targetStoreId)
            ->load($id);
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
