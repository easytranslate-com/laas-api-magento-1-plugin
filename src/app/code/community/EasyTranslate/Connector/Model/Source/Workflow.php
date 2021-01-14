<?php

declare(strict_types=1);

use EasyTranslate\Workflow;

class EasyTranslate_Connector_Model_Source_Workflow
{
    public function getOptions(): array
    {
        /** @var EasyTranslate_Connector_Helper_Data $helper */
        $helper = Mage::helper('easytranslate');

        return [
            Workflow::TYPE_TRANSLATION              => $helper->__('Translation'),
            Workflow::TYPE_TRANSLATION_REVIEW       => $helper->__('Translation and review'),
            Workflow::TYPE_SELF_MACHINE_TRANSLATION => $helper->__('Translate yourself'),
        ];
    }

    public function toOptionArray(): array
    {
        $options = [];
        foreach ($this->getOptions() as $value => $label) {
            $options[] = ['value' => $value, 'label' => $label];
        }

        return $options;
    }
}
