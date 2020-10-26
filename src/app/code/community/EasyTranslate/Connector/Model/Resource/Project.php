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
        $this->_saveProjectStores($project);
        $this->_saveProjectProducts($project);
        $this->_saveProjectCategories($project);

        return parent::_afterSave($project);
    }

    protected function _saveProjectStores(EasyTranslate_Connector_Model_Project $project): void
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
    }

    protected function _saveProjectProducts(EasyTranslate_Connector_Model_Project $project): void
    {
        $projectId   = (int)$project->getId();
        $newProducts = $project->getData('posted_products');

        if ($newProducts === null) {
            return;
        }

        $oldProducts = $this->getProducts($project);

        $table  = $this->getTable('easytranslate/project_product');
        $insert = array_diff($newProducts, $oldProducts);
        $delete = array_diff($oldProducts, $newProducts);

        if (!empty($delete)) {
            $cond = [
                'product_id IN(?)' => $delete,
                'project_id=?'     => $projectId
            ];
            $this->_getWriteAdapter()->delete($table, $cond);
        }

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $productId) {
                $data[] = [
                    'project_id' => $projectId,
                    'product_id' => (int)$productId
                ];
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
    }

    protected function _saveProjectCategories(EasyTranslate_Connector_Model_Project $project): void
    {
        $projectId     = (int)$project->getId();
        $newCategories = $project->getData('posted_categories');

        if ($newCategories === null) {
            return;
        }

        $oldCategories = $this->getCategories($project);

        $table  = $this->getTable('easytranslate/project_category');
        $insert = array_diff($newCategories, $oldCategories);
        $delete = array_diff($oldCategories, $newCategories);

        if (!empty($delete)) {
            $cond = [
                'category_id IN(?)' => $delete,
                'project_id=?'      => $projectId
            ];
            $this->_getWriteAdapter()->delete($table, $cond);
        }

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $productId) {
                $data[] = [
                    'project_id'  => $projectId,
                    'category_id' => (int)$productId
                ];
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
    }

    public function getProducts(EasyTranslate_Connector_Model_Project $project): array
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('easytranslate/project_product'), ['product_id'])
            ->where('project_id = :project_id');
        $bind   = ['project_id' => (int)$project->getId()];

        return $this->_getWriteAdapter()->fetchCol($select, $bind);
    }

    public function getCategories(EasyTranslate_Connector_Model_Project $project): array
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('easytranslate/project_category'), ['category_id'])
            ->where('project_id = :project_id');
        $bind   = ['project_id' => (int)$project->getId()];

        return $this->_getWriteAdapter()->fetchCol($select, $bind);
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
