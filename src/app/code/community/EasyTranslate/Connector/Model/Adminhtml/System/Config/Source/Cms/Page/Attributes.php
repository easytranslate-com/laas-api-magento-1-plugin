<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Adminhtml_System_Config_Source_Cms_Page_Attributes
{
    public function toOptionArray(): array
    {
        /** @var Mage_Cms_Helper_Data $helper */
        $helper = Mage::helper('cms');

        // TODO provide possibility to extend attributes via XML

        return [
            [
                'value' => 'title',
                'label' => $helper->__('Page Title')
            ],
            [
                'value' => 'content',
                'label' => $helper->__('Content')
            ],
            [
                'value' => 'meta_description',
                'label' => $helper->__('Meta Description')
            ],
            [
                'value' => 'content_heading',
                'label' => $helper->__('Content Heading')
            ],
            [
                'value' => 'identifier',
                'label' => $helper->__('URL Key')
            ]
        ];
    }
}
