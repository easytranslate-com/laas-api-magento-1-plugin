<?php

declare(strict_types=1);

use EasyTranslate\Api\TaskApi;

class EasyTranslate_Connector_Model_Cron_Handler
{
    public function __construct()
    {
        EasyTranslate_Connector_Model_Autoloader::createAndRegister();
    }

    public function handle(): void
    {
        /** @var EasyTranslate_Connector_Model_Task_Queue $task */
        $task = Mage::getModel('easytranslate/task_queue')
            ->getCollection()
            ->addFieldToFilter('processed_at', ['null' => true])
            ->setOrder('created_at', Varien_Data_Collection::SORT_ORDER_ASC)
            ->setPageSize(1)
            ->setCurPage(1)
            ->getFirstItem();
        if (!$task->getId()) {
            return;
        }
        $project       = $task->getProject();
        $targetContent = $this->_loadTargetContent($project, $task);
        $sourceStoreId = (int)$project->getData('source_store_id');
        // TODO retrieve correct external locale code
        $externalTargetLocaleCode = $task->getData('target_language');
        $targetStores             = $this->_getCorrectTargetStores($project, $externalTargetLocaleCode);
        Mage::getModel('easytranslate/content_importer')->import($targetContent, $sourceStoreId, $targetStores);
        $task->setData('processed_at', Mage::getSingleton('core/date')->gmtDate());
        $task->save();
    }

    protected function _loadTargetContent(
        EasyTranslate_Connector_Model_Project $project,
        EasyTranslate_Connector_Model_Task_Queue $task
    ): array {
        $configuration = Mage::getModel('easytranslate/config')->getApiConfiguration();
        $taskApi       = new TaskApi($configuration);
        /** @var EasyTranslate_Connector_Model_Bridge_Project $project */
        $bridgeProject = Mage::getModel('easytranslate/bridge_project', $project);
        $bridgeTask    = Mage::getModel('easytranslate/bridge_task', $task);

        return $taskApi->downloadTaskTarget($bridgeProject, $bridgeTask)->getData();
    }

    protected function _getCorrectTargetStores(
        EasyTranslate_Connector_Model_Project $project,
        string $externalTargetLocaleCode
    ): array {
        $targetStores            = [];
        $magentoTargetLocaleCode = Mage::getModel('easytranslate/locale_targetMapper')
            ->mapExternalCodeToMagentoCode($externalTargetLocaleCode);
        $allTargetStores         = $project->getData('target_stores');
        foreach ($allTargetStores as $targetStore) {
            $storeLocaleCode = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE, $targetStore);
            // import into all stores with the target locale
            if ($magentoTargetLocaleCode === $storeLocaleCode) {
                $targetStores[] = $targetStore;
            }
        }

        return $targetStores;
    }
}
