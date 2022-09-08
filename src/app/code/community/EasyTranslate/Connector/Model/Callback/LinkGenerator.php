<?php

class EasyTranslate_Connector_Model_Callback_LinkGenerator
{
    const SECRET_PARAM = 'secret';

    /**
     * @return string
     */
    public function generateLink(EasyTranslate_Connector_Model_Project $project)
    {
        $params = [
            self::SECRET_PARAM => $project->getData('secret'),
        ];

        return Mage::getUrl('easytranslate/callback/execute', $params);
    }
}
