<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Callback_LinkGenerator
{
    public const SECRET_PARAM = 'secret';

    public function generateLink(EasyTranslate_Connector_Model_Project $project): string
    {
        $params = [
            self::SECRET_PARAM => $project->getData('secret'),
        ];

        return Mage::getUrl('easytranslate/callback/execute', $params);
    }
}
