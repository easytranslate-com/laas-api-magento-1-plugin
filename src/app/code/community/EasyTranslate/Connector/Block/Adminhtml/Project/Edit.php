<?php

class EasyTranslate_Connector_Block_Adminhtml_Project_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'project_id';
        parent::__construct();
        $this->_blockGroup = 'easytranslate';
        $this->_controller = 'adminhtml_project';
        $this->setData('form_action_url', $this->getUrl('*/*/save'));
        $this->_updateButton('save', 'label', $this->_getHelper()->__('Save Project'));

        /** @var EasyTranslate_Connector_Model_Project $project */
        $project = Mage::registry('current_project');

        if (!$project) {
            $this->_addSaveAndContinueEditButton();
        } elseif ($project->canEditDetails()) {
            $this->_addSaveAndContinueEditButton();
            $this->_addSendButton();
        } elseif ($project->requiresPriceApproval()) {
            $this->_addAcceptPriceButton($project);
            $this->_addDeclinePriceButton($project);
            $this->_removeButton('save');
            $this->_removeButton('delete');
            $this->_removeButton('reset');
        } else {
            $this->_removeButton('save');
            $this->_removeButton('delete');
            $this->_removeButton('reset');
            $this->_addImportButtonIfAvailable($project);
        }
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        $project = Mage::registry('current_project');
        if ($project && $project->getId()) {
            return $this->_getHelper()->__('Edit Project "%s" (ID %s)', $project->getName(), $project->getId());
        }

        return $this->_getHelper()->__('New Project');
    }

    /**
     * @return void
     */
    protected function _addSaveAndContinueEditButton()
    {
        $this->_addButton('save_and_continue', [
            'label'   => $this->_getHelper()->__('Save and Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class'   => 'save',
        ], -100);
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * @return void
     */
    protected function _addSendButton()
    {
        $this->_addButton('send', [
            'label'   => $this->_getHelper()->__('Send To EasyTranslate'),
            'onclick' => 'sendToEasyTranslate()',
            'class'   => 'save',
        ], -100);
        $sendToEasyTranslateUrl = $this->getUrl('*/*/send');
        $this->_formScripts[]   = "
            function sendToEasyTranslate() {
                editForm.submit('$sendToEasyTranslateUrl');
            }
        ";
    }

    /**
     * @return void
     */
    protected function _addAcceptPriceButton(EasyTranslate_Connector_Model_Project $project)
    {
        $confirmMessage = $this->_getHelper()->__('Are you sure you want to accept the price for this project?');
        $acceptPriceUrl = $this->getUrl('*/*/acceptPrice', ['project_id' => $project->getId()]);
        $this->_addButton('accept_price', [
            'label'   => $this->_getHelper()->__('Accept Price'),
            'class'   => 'save',
            'onclick' => 'confirmSetLocation(\'' . Mage::helper('core')->jsQuoteEscape($confirmMessage) . '\', \''
                . $acceptPriceUrl . '\')',
        ]);
    }

    /**
     * @return void
     */
    protected function _addDeclinePriceButton(EasyTranslate_Connector_Model_Project $project)
    {
        $confirmMessage  = $this->_getHelper()->__('Are you sure you want to decline the price for this project?');
        $declinePriceUrl = $this->getUrl('*/*/declinePrice', ['project_id' => $project->getId()]);
        $this->_addButton('decline_price', [
            'label'   => $this->_getHelper()->__('Decline Price'),
            'class'   => 'cancel',
            'onclick' => 'confirmSetLocation(\'' . Mage::helper('core')->jsQuoteEscape($confirmMessage) . '\', \''
                . $declinePriceUrl . '\')',
        ]);
    }

    /**
     * @return \EasyTranslate_Connector_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('easytranslate');
    }

    /**
     * @return void
     */
    protected function _addImportButtonIfAvailable(EasyTranslate_Connector_Model_Project $project)
    {
        if ((bool)$project->getData('automatic_import') === false
            && $project->getTaskCollection()->addFieldToFilter('processed_at', ['null' => true])->getSize()) {
            $this->_addButton('import', [
                'label'   => $this->_getHelper()->__('Schedule for import'),
                'onclick' => 'scheduleForImport()',
                'class'   => 'save',
            ], -100);
            $scheduleImportUrl    = $this->getUrl('*/*/scheduleImport');
            $this->_formScripts[] = "function scheduleForImport() {editForm.submit('$scheduleImportUrl');}";
        }
    }
}
