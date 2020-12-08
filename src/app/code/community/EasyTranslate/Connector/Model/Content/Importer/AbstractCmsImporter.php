<?php

declare(strict_types=1);

abstract class EasyTranslate_Connector_Model_Content_Importer_AbstractCmsImporter
    extends EasyTranslate_Connector_Model_Content_Importer_AbstractImporter
{
    /**
     * @var array
     */
    protected $_objects;

    public function import(array $data, int $sourceStoreId, int $targetStoreId): void
    {
        parent::import($data, $sourceStoreId, $targetStoreId);
        $this->_bulkSave();
    }

    abstract protected function _importObject(
        string $id,
        array $attributes,
        int $sourceStoreId,
        int $targetStoreId
    ): void;

    protected function _bulkSave(): void
    {
        if ($this->_objects === null) {
            return;
        }
        $transaction = Mage::getModel('core/resource_transaction');
        foreach ($this->_objects as $object) {
            $transaction->addObject($object);
        }
        $transaction->save();
    }
}
