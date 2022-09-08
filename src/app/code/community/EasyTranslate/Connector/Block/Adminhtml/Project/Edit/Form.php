<?php

class EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * @return \EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']);
        $form->setData('use_container', true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
