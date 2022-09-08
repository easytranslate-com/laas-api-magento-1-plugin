<?php

abstract class EasyTranslate_Connector_Model_Content_Importer_AbstractImporter
{
    /**
     * @return void
     * @param int $sourceStoreId
     * @param int $targetStoreId
     */
    public function import(array $data, $sourceStoreId, $targetStoreId)
    {
        $sourceStoreId = (int) $sourceStoreId;
        $targetStoreId = (int) $targetStoreId;
        $lastId     = null;
        $attributes = [];
        foreach ($data as $key => $content) {
            $delimiter = EasyTranslate_Connector_Model_Content_Generator_AbstractGenerator::KEY_SEPARATOR;
            list($entityCode, $currentId, $attributeCode) = explode($delimiter, $key);
            if ($lastId !== null && $currentId !== $lastId) {
                $this->_importObject($lastId, $attributes, $sourceStoreId, $targetStoreId);
                $attributes = [];
            }
            $attributes[$attributeCode] = $content;
            $lastId                     = $currentId;
        }
        // make sure to import the last object as well
        if ($lastId !== null) {
            $this->_importObject($lastId, $attributes, $sourceStoreId, $targetStoreId);
        }
    }

    /**
     * @return void
     * @param string $id
     * @param int $sourceStoreId
     * @param int $targetStoreId
     */
    abstract protected function _importObject(
        $id,
        array $attributes,
        $sourceStoreId,
        $targetStoreId
    );
}
