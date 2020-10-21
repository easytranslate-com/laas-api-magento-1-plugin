<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Resource_Project_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct(): void
    {
        $this->_init('easytranslate/project');
        $this->_map['fields']['target_store'] = 'target_store_table.target_store_id';
    }

    public function addTargetStoreFilter(int $store): EasyTranslate_Connector_Model_Resource_Project_Collection
    {
        $this->addFilter('target_store', $store, 'public');

        return $this;
    }

    protected function _renderFiltersBefore(): void
    {
        if ($this->getFilter('target_store')) {
            $this->getSelect()->join(
                ['target_store_table' => $this->getTable('easytranslate/project_target_store')],
                'main_table.project_id = target_store_table.project_id',
                []
            )->group('main_table.project_id');

            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }

        parent::_renderFiltersBefore();
    }
}
