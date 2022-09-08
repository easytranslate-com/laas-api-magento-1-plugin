<?php

use EasyTranslate\Api\TaskApi;

class EasyTranslate_Connector_Model_Cron_Handler
{
    public function __construct()
    {
        EasyTranslate_Connector_Model_Autoloader::createAndRegister();
    }

    /**
     * @return void
     */
    public function handle()
    {
        /** @var EasyTranslate_Connector_Model_Task $task */
        $task = Mage::getModel('easytranslate/task')
            ->getCollection()
            ->addFieldToFilter('processed_at', ['null' => true])
            ->addFieldToFilter('content_link', ['notnull' => true])
            ->join(
                ['project' => 'easytranslate/project'],
                'main_table.project_id = project.project_id',
                ['automatic_import']
            )
            ->addFieldToFilter('automatic_import', 1)
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
        $targetStoreId = (int)$task->getData('store_id');
        Mage::getModel('easytranslate/content_importer')->import($targetContent, $sourceStoreId, $targetStoreId);
        $task->setData('processed_at', Mage::getSingleton('core/date')->gmtDate());
        $task->save();
    }

    /**
     * @return mixed[]
     */
    protected function _loadTargetContent(
        EasyTranslate_Connector_Model_Project $project,
        EasyTranslate_Connector_Model_Task $task
    ) {
        $contentLink   = $task->getData('content_link');
        $initialTaskId = $task->getData('external_id');
        $currentTaskId = $this->_retrieveTaskIdFromLink($contentLink) !== null ? $this->_retrieveTaskIdFromLink($contentLink) : $initialTaskId;
        // make sure that we retrieve the content with the current task ID, not the initial one!
        $task->setData('external_id', $currentTaskId);

        $configuration = Mage::getModel('easytranslate/config')->getApiConfiguration();
        $taskApi       = new TaskApi($configuration);
        /** @var EasyTranslate_Connector_Model_Bridge_Project $project */
        $bridgeProject = Mage::getModel('easytranslate/bridge_project', $project);
        $bridgeTask    = Mage::getModel('easytranslate/bridge_task', $task);
        $targetContent = $taskApi->downloadTaskTarget($bridgeProject, $bridgeTask)->getData();

        // make sure the external ID is not updated in the Magento database
        // future callbacks will still reference the initial task ID!
        $task->setData('external_id', $initialTaskId);

        return $targetContent;
    }

    /**
     * @return string|null
     * @param string $link
     */
    protected function _retrieveTaskIdFromLink($link)
    {
        $link = (string) $link;
        $matches = [];
        if (preg_match('/\/tasks\/([^\/]*)\//', $link, $matches) && count($matches) > 1) {
            return $matches[1];
        }

        return null;
    }
}
