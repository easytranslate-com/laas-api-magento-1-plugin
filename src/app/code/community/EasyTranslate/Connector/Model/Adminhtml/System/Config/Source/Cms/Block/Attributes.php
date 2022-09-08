<?php

class EasyTranslate_Connector_Model_Adminhtml_System_Config_Source_Cms_Block_Attributes
{
    /**
     * @return mixed[]
     */
    public function toOptionArray()
    {
        /** @var Mage_Cms_Helper_Data $helper */
        $helper = Mage::helper('cms');

        // TODO provide possibility to extend attributes via XML

        return [
            [
                'value' => 'title',
                'label' => $helper->__('Title')
            ],
            [
                'value' => 'content',
                'label' => $helper->__('Content')
            ]
        ];
    }
}
