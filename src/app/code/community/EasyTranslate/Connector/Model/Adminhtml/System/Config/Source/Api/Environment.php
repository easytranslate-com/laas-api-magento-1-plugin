<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Adminhtml_System_Config_Source_Api_Environment
{
    public const SANDBOX = 'sandbox';

    public const LIVE = 'live';

    public function toOptionArray(): array
    {
        return [
            ['value' => self::SANDBOX, 'label' => Mage::helper('easytranslate')->__('Sandbox')],
            ['value' => self::LIVE, 'label' => Mage::helper('easytranslate')->__('Live')],
        ];
    }
}
