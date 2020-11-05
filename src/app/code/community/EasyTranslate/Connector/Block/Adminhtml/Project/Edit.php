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
        /** @var EasyTranslate_Connector_Model_Project $project */
        $project = Mage::registry('current_project');
        if (!$project || $project->canEditDetails()) {
            $this->_updateButton('save', 'label', Mage::helper('easytranslate')->__('Save Project'));
            $this->_addButton('save_and_continue', [
                'label'   => Mage::helper('easytranslate')->__('Save and Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ], -100);
            if ($project && $project->getId()) {
                $this->_addButton('send', [
                    'label'   => Mage::helper('easytranslate')->__('Send To EasyTranslate'),
                    'onclick' => 'sendToEasyTranslate()',
                    'class'   => 'save',
                ], -100);
            }

            $sendToEasyTranslateUrl = $this->getUrl('*/*/send');
            $this->_formScripts[]   = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
            function sendToEasyTranslate() {
                editForm.submit('$sendToEasyTranslateUrl');
            }
        ";
        } else {
            $this->_removeButton('save');
            $this->_removeButton('reset');
        }
    }

    public function getHeaderText()
    {
        $project = Mage::registry('current_project');
        if ($project && $project->getId()) {
            return Mage::helper('easytranslate')
                ->__('Edit Project "%s" (ID %s)', $project->getName(), $project->getId());
        }

        return Mage::helper('easytranslate')->__('New Project');
    }
}
