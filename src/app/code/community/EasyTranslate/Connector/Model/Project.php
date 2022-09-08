<?php

use EasyTranslate\ProjectInterface;
use EasyTranslate\TaskInterface;

class EasyTranslate_Connector_Model_Project extends Mage_Core_Model_Abstract
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('easytranslate/project');
    }

    /**
     * @return mixed[]
     */
    public function getProducts()
    {
        if (!$this->getId()) {
            return [];
        }

        $products = $this->getData('products');
        if (is_null($products)) {
            $products = $this->getResource()->getProducts($this);
            $this->setData('products', $products);
        }

        return $products;
    }

    /**
     * @return mixed[]
     */
    public function getCategories()
    {
        if (!$this->getId()) {
            return [];
        }

        $categories = $this->getData('categories');
        if (is_null($categories)) {
            $categories = $this->getResource()->getCategories($this);
            $this->setData('categories', $categories);
        }

        return $categories;
    }

    /**
     * @return mixed[]
     */
    public function getCmsBlocks()
    {
        if (!$this->getId()) {
            return [];
        }

        $cmsBlocks = $this->getData('cmsBlocks');
        if (is_null($cmsBlocks)) {
            $cmsBlocks = $this->getResource()->getCmsBlocks($this);
            $this->setData('cmsBlocks', $cmsBlocks);
        }

        return $cmsBlocks;
    }

    /**
     * @return mixed[]
     */
    public function getCmsPages()
    {
        if (!$this->getId()) {
            return [];
        }

        $cmsPages = $this->getData('cmsPages');
        if (is_null($cmsPages)) {
            $cmsPages = $this->getResource()->getCmsPages($this);
            $this->setData('cmsPages', $cmsPages);
        }

        return $cmsPages;
    }

    /**
     * @return mixed[]
     */
    public function getTasks()
    {
        if (!$this->getId()) {
            return [];
        }

        $tasks = $this->getData('tasks');
        if (is_null($tasks)) {
            $tasks = $this->getTaskCollection();
            $this->setData('tasks', iterator_to_array($tasks, false));
        }

        return $tasks;
    }

    /**
     * @return \EasyTranslate_Connector_Model_Resource_Task_Collection
     */
    public function getTaskCollection()
    {
        return Mage::getModel('easytranslate/task')
            ->getCollection()
            ->addFieldToFilter('project_id', $this->getId());
    }

    /**
     * @return bool
     */
    public function canEditDetails()
    {
        return !$this->getId() || $this->getData('status') === EasyTranslate_Connector_Model_Source_Status::OPEN;
    }

    /**
     * @return bool
     */
    public function requiresPriceApproval()
    {
        return $this->getId()
            && $this->getData('status') === EasyTranslate_Connector_Model_Source_Status::PRICE_APPROVAL_REQUEST;
    }

    /**
     * @return void
     */
    public function importDataFromExternalProject(ProjectInterface $externalProject)
    {
        $this->setData('external_id', $externalProject->getId());
        $this->setData('price', $externalProject->getPrice());
        $this->setData('currency', $externalProject->getCurrency());
        /** @var TaskInterface $externalTask */
        foreach ($externalProject->getTasks() as $externalTask) {
            $targetLanguage = $externalTask->getTargetLanguage();
            $targetStoreIds = $this->_getStoreIdsByTargetLanguage($targetLanguage);
            // one external task (language-specific) can result in multiple Magento tasks (store-specific)
            foreach ($targetStoreIds as $targetStoreId) {
                $magentoTask = Mage::getModel('easytranslate/task');
                $magentoTask->setData('project_id', $this->getId());
                $magentoTask->setData('external_id', $externalTask->getId());
                $magentoTask->setData('store_id', $targetStoreId);
                $magentoTask->setData('content_link', $externalTask->getTargetContent());
                $magentoTask->save();
            }
        }
        // make sure that tasks are re-retrieved
        $this->unsetData('tasks');
    }

    /**
     * @param string $targetLanguage
     * @return mixed[]
     */
    protected function _getStoreIdsByTargetLanguage($targetLanguage)
    {
        $targetLanguage = (string) $targetLanguage;
        $targetMagentoLocale = Mage::getModel('easytranslate/locale_targetMapper')
            ->mapExternalCodeToMagentoCode($targetLanguage);
        $storeIds            = [];
        $potentialStoreIds   = $this->getData('target_stores');
        foreach ($potentialStoreIds as $potentialStoreId) {
            $potentialStoreLocale = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE,
                $potentialStoreId);
            if ($potentialStoreLocale === $targetMagentoLocale) {
                $storeIds[] = $potentialStoreId;
            }
        }

        return $storeIds;
    }

    /**
     * @return \EasyTranslate_Connector_Model_Project
     */
    public function updateTasksStatus()
    {
        $numberOfTasks = $this->getTaskCollection()->getSize();
        if ($numberOfTasks === 0) {
            return $this;
        }
        $numberOfCompletedTasks = $this->getTaskCollection()
            ->addFieldToFilter('processed_at', ['notnull' => true])
            ->getSize();
        if ($numberOfTasks === $numberOfCompletedTasks) {
            $this->setData('status', EasyTranslate_Connector_Model_Source_Status::FINISHED);
        } elseif ($numberOfCompletedTasks > 0) {
            $this->setData('status', EasyTranslate_Connector_Model_Source_Status::PARTIALLY_FINISHED);
        }

        return $this;
    }
}
