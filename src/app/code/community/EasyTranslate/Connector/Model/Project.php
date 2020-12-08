<?php

declare(strict_types=1);

use EasyTranslate\ProjectInterface;
use EasyTranslate\TaskInterface;

class EasyTranslate_Connector_Model_Project extends Mage_Core_Model_Abstract
{
    protected function _construct(): void
    {
        $this->_init('easytranslate/project');
    }

    public function getProducts(): array
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

    public function getCategories(): array
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

    public function getCmsBlocks(): array
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

    public function getCmsPages(): array
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

    public function getTasks(): array
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

    public function getTaskCollection(): EasyTranslate_Connector_Model_Resource_Task_Collection
    {
        return Mage::getModel('easytranslate/task')
            ->getCollection()
            ->addFieldToFilter('project_id', $this->getId());
    }

    public function canEditDetails(): bool
    {
        return !$this->getId() || $this->getData('status') === EasyTranslate_Connector_Model_Source_Status::OPEN;
    }

    public function requiresPriceApproval(): bool
    {
        return $this->getId()
            && $this->getData('status') === EasyTranslate_Connector_Model_Source_Status::PRICE_APPROVAL_REQUEST;
    }

    public function importDataFromExternalProject(ProjectInterface $externalProject): void
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

    protected function _getStoreIdsByTargetLanguage(string $targetLanguage): array
    {
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

    public function updateTasksStatus(): EasyTranslate_Connector_Model_Project
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
