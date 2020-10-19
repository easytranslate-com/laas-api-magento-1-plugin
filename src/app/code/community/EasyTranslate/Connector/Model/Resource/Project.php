<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Resource_Project extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct(): void
    {
        $this->_init('easytranslate/project', 'project_id');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $project): EasyTranslate_Connector_Model_Resource_Project
    {
        if (!$project->getId()) {
            $project->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
        }
        $project->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());

        // make sure that we do not translate from and to the same language
        $sourceStoreId  = $project->getData('source_store_id');
        $targetStoreIds = $project->getData('target_stores');
        $targetStoreIds = array_diff($targetStoreIds, [$sourceStoreId]);
        $project->setData('target_stores', $targetStoreIds);

        return parent::_beforeSave($project);
    }

    protected function _afterSave(Mage_Core_Model_Abstract $project): EasyTranslate_Connector_Model_Resource_Project
    {
        $projectId       = (int)$project->getId();
        $oldTargetStores = $this->_lookupTargetStoreIds($projectId);
        $newTargetStores = $project->getData('target_stores');

        $table  = $this->getTable('easytranslate/project_target_store');
        $insert = array_diff($newTargetStores, $oldTargetStores);
        $delete = array_diff($oldTargetStores, $newTargetStores);

        if ($delete) {
            $where = [
                'project_id = ?'         => $projectId,
                'target_store_id IN (?)' => $delete
            ];

            $this->_getWriteAdapter()->delete($table, $where);
        }

        if ($insert) {
            $data = [];

            foreach ($insert as $storeId) {
                $data[] = [
                    'project_id'      => $projectId,
                    'target_store_id' => (int)$storeId
                ];
            }

            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }

        return parent::_afterSave($project);
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $project)
    {
        if ($project->getId()) {
            $targetStoreIds = $this->_lookupTargetStoreIds((int)$project->getId());
            $project->setData('target_stores', $targetStoreIds);
        }

        return parent::_afterLoad($project);
    }

    protected function _lookupTargetStoreIds(int $id): array
    {
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from($this->getTable('easytranslate/project_target_store'), 'target_store_id')
            ->where('project_id = :project_id');

        $binds = [
            ':project_id' => $id
        ];

        return $adapter->fetchCol($select, $binds);
    }
}
