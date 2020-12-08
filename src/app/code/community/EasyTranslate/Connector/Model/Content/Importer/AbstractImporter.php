<?php

declare(strict_types=1);

abstract class EasyTranslate_Connector_Model_Content_Importer_AbstractImporter
{
    public function import(array $data, int $sourceStoreId, int $targetStoreId): void
    {
        $lastId     = null;
        $attributes = [];
        foreach ($data as $key => $content) {
            $delimiter = EasyTranslate_Connector_Model_Content_Generator_AbstractGenerator::KEY_SEPARATOR;
            [$entityCode, $currentId, $attributeCode] = explode($delimiter, $key);
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

    abstract protected function _importObject(
        string $id,
        array $attributes,
        int $sourceStoreId,
        int $targetStoreId
    ): void;
}
