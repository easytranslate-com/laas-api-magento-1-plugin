<?php

declare(strict_types=1);

class EasyTranslate_Connector_Block_Adminhtml_Project_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'easytranslate';
        $this->_controller = 'adminhtml_project';
        $this->setData('form_action_url', $this->getUrl('*/*/save'));
        $this->_updateButton('save', 'label', Mage::helper('easytranslate')->__('Save Project'));
        $this->_addButton('save_and_continue', [
            'label'   => Mage::helper('easytranslate')->__('Save and Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class'   => 'save',
        ], -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        $model = Mage::registry('current_project');
        if ($model && $model->getId()) {
            return Mage::helper('easytranslate')->__('Edit Project "%s" (ID %s)', $model->getName(), $model->getId());
        }

        return Mage::helper('easytranslate')->__('New Project');
    }
}
