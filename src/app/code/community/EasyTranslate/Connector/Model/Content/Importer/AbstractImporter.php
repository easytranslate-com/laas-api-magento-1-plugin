<?php

declare(strict_types=1);

abstract class EasyTranslate_Connector_Model_Content_Importer_AbstractImporter
{
    /**
     * @var array
     */
    protected $_objects;

    public function import(array $data, array $storeIds): void
    {
        $lastId     = null;
        $attributes = [];
        foreach ($data as $key => $content) {
            $delimiter = EasyTranslate_Connector_Model_Content_Generator_AbstractGenerator::KEY_SEPARATOR;
            [$entityCode, $currentId, $attributeCode] = explode($delimiter, $key);
            if ($lastId !== null && $currentId !== $lastId) {
                $this->_createObjects($lastId, $attributes, $storeIds);
                $attributes = [];
            }
            $attributes[$attributeCode] = $content;
        }
        $this->_bulkSave();
    }

    abstract protected function _createObjects(string $lastId, array $attributes, array $storeIds): void;

    protected function _bulkSave(): void
    {
        $transaction = Mage::getModel('core/resource_transaction');
        foreach ($this->_objects as $object) {
            $transaction->addObject($object);
        }
        $transaction->save();
    }
}
