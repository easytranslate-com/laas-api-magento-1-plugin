<?php

use EasyTranslate\Api\Environment as ApiEnvironment;

class EasyTranslate_Connector_Model_Adminhtml_System_Config_Source_Api_Environment
{
    /**
     * @return mixed[]
     */
    public function toOptionArray()
    {
        return [
            ['value' => ApiEnvironment::SANDBOX, 'label' => Mage::helper('easytranslate')->__('Sandbox')],
            ['value' => ApiEnvironment::LIVE, 'label' => Mage::helper('easytranslate')->__('Live')],
        ];
    }
}
