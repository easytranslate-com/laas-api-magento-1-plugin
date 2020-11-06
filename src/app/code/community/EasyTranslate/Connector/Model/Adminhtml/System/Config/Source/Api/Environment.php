<?php

declare(strict_types=1);

use EasyTranslate\Api\Environment as ApiEnvironment;

class EasyTranslate_Connector_Model_Adminhtml_System_Config_Source_Api_Environment
{
    public function toOptionArray(): array
    {
        return [
            ['value' => ApiEnvironment::SANDBOX, 'label' => Mage::helper('easytranslate')->__('Sandbox')],
            ['value' => ApiEnvironment::LIVE, 'label' => Mage::helper('easytranslate')->__('Live')],
        ];
    }
}
