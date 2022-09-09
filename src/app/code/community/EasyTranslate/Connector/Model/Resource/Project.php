<?php

class EasyTranslate_Connector_Model_Resource_Project extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('easytranslate/project', 'project_id');
    }

    /**
     * @return \EasyTranslate_Connector_Model_Resource_Project
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $project)
    {
        if (!$project->getId()) {
            $project->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
        }
        $project->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());

        if ($project->getData('secret') === null) {
            $strong_result = true;
            $project->setData('secret', bin2hex(openssl_random_pseudo_bytes(32, $strong_result)));
        }

        // make sure that we do not translate from and to the same language
        $sourceStoreId  = $project->getData('source_store_id');
        $targetStoreIds = $project->getData('target_stores');
        $targetStoreIds = array_diff($targetStoreIds, [$sourceStoreId]);
        $project->setData('target_stores', $targetStoreIds);

        return parent::_beforeSave($project);
    }

    /**
     * @return \EasyTranslate_Connector_Model_Resource_Project
     */
    protected function _afterSave(Mage_Core_Model_Abstract $project)
    {
        $this->_saveProjectStores($project);
        $this->_saveProjectProducts($project);
        $this->_saveProjectCategories($project);
        $this->_saveProjectCmsBlocks($project);
        $this->_saveProjectCmsPages($project);

        return parent::_afterSave($project);
    }

    /**
     * @return void
     */
    protected function _saveProjectStores(EasyTranslate_Connector_Model_Project $project)
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

    /**
     * @return void
     */
    protected function _saveProjectProducts(EasyTranslate_Connector_Model_Project $project)
    {
        $projectId   = (int)$project->getId();
        $newProducts = $project->getData('posted_products');

        if ($newProducts === null) {
            return;
        }

        $oldProducts = $this->getProducts($project);

        if (empty($newProducts) && empty($oldProducts)) {
            return;
        }

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

    /**
     * @return void
     */
    protected function _saveProjectCategories(EasyTranslate_Connector_Model_Project $project)
    {
        $projectId     = (int)$project->getId();
        $newCategories = $project->getData('posted_categories');

        if ($newCategories === null) {
            return;
        }

        $oldCategories = $this->getCategories($project);
        if (empty($newCategories) && empty($oldCategories)) {
            return;
        }

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
            foreach ($insert as $categoryId) {
                $data[] = [
                    'project_id'  => $projectId,
                    'category_id' => (int)$categoryId
                ];
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
    }

    /**
     * @return void
     */
    protected function _saveProjectCmsBlocks(EasyTranslate_Connector_Model_Project $project)
    {
        $projectId    = (int)$project->getId();
        $newCmsBlocks = $project->getData('posted_cmsBlocks');

        if ($newCmsBlocks === null) {
            return;
        }

        $oldCmsBlocks = $this->getCmsBlocks($project);
        if (empty($oldCmsBlocks) && empty($newCmsBlocks)) {
            return;
        }

        $table  = $this->getTable('easytranslate/project_cms_block');
        $insert = array_diff($newCmsBlocks, $oldCmsBlocks);
        $delete = array_diff($oldCmsBlocks, $newCmsBlocks);

        if (!empty($delete)) {
            $cond = [
                'block_id IN(?)' => $delete,
                'project_id=?'   => $projectId
            ];
            $this->_getWriteAdapter()->delete($table, $cond);
        }

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $cmsBlockId) {
                $data[] = [
                    'project_id' => $projectId,
                    'block_id'   => (int)$cmsBlockId
                ];
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
    }

    /**
     * @return void
     */
    protected function _saveProjectCmsPages(EasyTranslate_Connector_Model_Project $project)
    {
        $projectId   = (int)$project->getId();
        $newCmsPages = $project->getData('posted_cmsPages');

        if ($newCmsPages === null) {
            return;
        }

        $oldCmsPages = $this->getCmsPages($project);
        if (empty($oldCmsPages) && empty($newCmsPages)) {
            return;
        }

        $table  = $this->getTable('easytranslate/project_cms_page');
        $insert = array_diff($newCmsPages, $oldCmsPages);
        $delete = array_diff($oldCmsPages, $newCmsPages);

        if (!empty($delete)) {
            $cond = [
                'page_id IN(?)' => $delete,
                'project_id=?'  => $projectId
            ];
            $this->_getWriteAdapter()->delete($table, $cond);
        }

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $cmsPageId) {
                $data[] = [
                    'project_id' => $projectId,
                    'page_id'    => (int)$cmsPageId
                ];
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
    }

    /**
     * @return mixed[]
     */
    public function getProducts(EasyTranslate_Connector_Model_Project $project)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('easytranslate/project_product'), ['product_id'])
            ->where('project_id = :project_id');
        $bind   = ['project_id' => (int)$project->getId()];

        return $this->_getWriteAdapter()->fetchCol($select, $bind);
    }

    /**
     * @return mixed[]
     */
    public function getCategories(EasyTranslate_Connector_Model_Project $project)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('easytranslate/project_category'), ['category_id'])
            ->where('project_id = :project_id');
        $bind   = ['project_id' => (int)$project->getId()];

        return $this->_getWriteAdapter()->fetchCol($select, $bind);
    }

    /**
     * @return mixed[]
     */
    public function getCmsBlocks(EasyTranslate_Connector_Model_Project $project)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('easytranslate/project_cms_block'), ['block_id'])
            ->where('project_id = :project_id');
        $bind   = ['project_id' => (int)$project->getId()];

        return $this->_getWriteAdapter()->fetchCol($select, $bind);
    }

    /**
     * @return mixed[]
     */
    public function getCmsPages(EasyTranslate_Connector_Model_Project $project)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('easytranslate/project_cms_page'), ['page_id'])
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

    /**
     * @param int $id
     * @return mixed[]
     */
    protected function _lookupTargetStoreIds($id)
    {
        $id = (int) $id;
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
