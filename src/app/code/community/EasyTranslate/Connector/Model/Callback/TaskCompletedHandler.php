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
        $tasks = Mage::getModel('easytranslate/task')
            ->getCollection()
            ->addFieldToFilter('external_id', $response->getTask()->getId());
        foreach ($tasks as $task) {
            $task->setData('content_link', $response->getTask()->getTargetContent());
            // make sure the task is imported again if there is another update
            $task->setData('processed_at', null);
            $task->save();
        }
    }
}
