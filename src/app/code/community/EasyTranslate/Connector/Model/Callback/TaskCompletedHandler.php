<?php

declare(strict_types=1);

use EasyTranslate\Api\Callback\DataConverter\TaskCompletedConverter;

class EasyTranslate_Connector_Model_Callback_TaskCompletedHandler
{
    public function handle(array $data): void
    {
        $secret    = $data[EasyTranslate_Connector_Model_Callback_LinkGenerator::SECRET_PARAM];
        $converter = new TaskCompletedConverter();
        $response  = $converter->convert($data);
        $project   = Mage::getModel('easytranslate/project')->load($response->getProjectId(), 'external_id');
        if ($project->getData('secret') !== $secret) {
            Mage::throwException('Secret does not match.');
        }
        $targetLanguage = $response->getTargetLanguage();
        $targetStoreIds = $this->_getStoreIdsByTargetLanguage($project, $targetLanguage);
        foreach ($targetStoreIds as $targetStoreId) {
            $task = Mage::getModel('easytranslate/task_queue');
            $task->setData('project_id', $project->getId());
            $task->setData('store_id', $targetStoreId);
            $task->setData('content_link', $response->getTargetContent());
            $task->save();
        }
    }

    protected function _getStoreIdsByTargetLanguage(
        EasyTranslate_Connector_Model_Project $project,
        string $targetLanguage
    ): array {
        $targetMagentoLocale = Mage::getModel('easytranslate/locale_mapper')
            ->mapExternalCodeToMagentoCode($targetLanguage);
        $storeIds            = [];
        $potentialStoreIds   = $project->getData('target_stores');
        foreach ($potentialStoreIds as $potentialStoreId) {
            $potentialStoreLocale = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE,
                $potentialStoreId);
            if ($potentialStoreLocale === $targetMagentoLocale) {
                $storeIds[] = $potentialStoreId;
            }
        }

        return $storeIds;
    }
}
