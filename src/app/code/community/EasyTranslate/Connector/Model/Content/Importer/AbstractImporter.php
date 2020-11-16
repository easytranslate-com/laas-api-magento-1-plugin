<?php

declare(strict_types=1);

abstract class EasyTranslate_Connector_Model_Content_Importer_AbstractImporter
{
    /**
     * @var array
     */
    protected $_objects;

    public function import(array $data, int $sourceStoreId, array $targetStoreIds): void
    {
        $lastId     = null;
        $attributes = [];
        foreach ($data as $key => $content) {
            $delimiter = EasyTranslate_Connector_Model_Content_Generator_AbstractGenerator::KEY_SEPARATOR;
            [$entityCode, $currentId, $attributeCode] = explode($delimiter, $key);
            if ($lastId !== null && $currentId !== $lastId) {
                $this->_createObjects($lastId, $attributes, $sourceStoreId, $targetStoreIds);
                $attributes = [];
            }
            $attributes[$attributeCode] = $content;
            $lastId                     = $currentId;
        }
        // make sure to import the last entity as well
        if ($lastId !== null) {
            $this->_createObjects($lastId, $attributes, $sourceStoreId, $targetStoreIds);
        }
        $this->_bulkSave();
    }

    abstract protected function _createObjects(
        string $id,
        array $attributes,
        int $sourceStoreId,
        array $targetStoreIds
    ): void;

    protected function _bulkSave(): void
    {
        $transaction = Mage::getModel('core/resource_transaction');
        foreach ($this->_objects as $object) {
            $transaction->addObject($object);
        }
        $transaction->save();
    }
}
