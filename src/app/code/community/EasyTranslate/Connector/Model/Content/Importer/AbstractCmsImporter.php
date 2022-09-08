<?php

abstract class EasyTranslate_Connector_Model_Content_Importer_AbstractCmsImporter
    extends EasyTranslate_Connector_Model_Content_Importer_AbstractImporter
{
    /**
     * @var array
     */
    protected $_objects;

    /**
     * @return void
     * @param int $sourceStoreId
     * @param int $targetStoreId
     */
    public function import(array $data, $sourceStoreId, $targetStoreId)
    {
        $sourceStoreId = (int) $sourceStoreId;
        $targetStoreId = (int) $targetStoreId;
        parent::import($data, $sourceStoreId, $targetStoreId);
        $this->_bulkSave();
    }

    /**
     * @return void
     */
    protected function _bulkSave()
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
