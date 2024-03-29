<?php

declare(strict_types=1);

use EasyTranslate\Api\Callback\DataConverter\PriceApprovalConverter;

class EasyTranslate_Connector_Model_Callback_PriceApprovalRequestHandler
{
    public function handle(array $data): void
    {
        $secret    = $data[EasyTranslate_Connector_Model_Callback_LinkGenerator::SECRET_PARAM];
        $converter = new PriceApprovalConverter();
        $response  = $converter->convert($data);
        $project   = Mage::getModel('easytranslate/project')->load($response->getProjectId(), 'external_id');
        if ($project->getData('secret') !== $secret) {
            Mage::throwException('Secret does not match.');
        }
        $project->setData('price', $response->getPrice());
        $project->setData('currency', $response->getCurrency());
        $project->setData('status', EasyTranslate_Connector_Model_Source_Status::PRICE_APPROVAL_REQUEST);
        $project->save();
    }
}
