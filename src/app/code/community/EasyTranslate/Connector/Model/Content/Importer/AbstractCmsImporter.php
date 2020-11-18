<?php

declare(strict_types=1);

abstract class EasyTranslate_Connector_Model_Content_Importer_AbstractCmsImporter
    extends EasyTranslate_Connector_Model_Content_Importer_AbstractImporter
{
    /**
     * @var array
     */
    protected $_objects;

    public function import(array $data, int $sourceStoreId, array $targetStoreIds): void
    {
        parent::import($data, $sourceStoreId, $targetStoreIds);
        $this->_bulkSave();
    }

    abstract protected function _importObject(
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
