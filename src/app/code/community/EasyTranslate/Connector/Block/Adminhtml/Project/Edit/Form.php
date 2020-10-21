<?php

declare(strict_types=1);

class EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm(): EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Form
    {
        $form = new Varien_Data_Form(['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']);
        $form->setData('use_container', true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
