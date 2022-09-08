<?php

class EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * @return \EasyTranslate_Connector_Helper_Data
     */
    protected function _getHelper()
    {
        return $this->helper('easytranslate');
    }

    /**
     * @return \EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_General
     */
    protected function _prepareForm()
    {
        /** @var EasyTranslate_Connector_Model_Project $project */
        $project = Mage::registry('current_project');
        $canEdit = !$project || $project->canEditDetails();
        $form    = new Varien_Data_Form();

        $fieldset = $form->addFieldset('project_information', [
            'legend' => $this->_getHelper()->__('Project Information')
        ]);

        if ($project && $project->getId()) {
            $modelPk = $project->getResource()->getIdFieldName();
            $fieldset->addField($modelPk, 'hidden', [
                'name' => $modelPk,
            ]);
        }

        $fieldset->addField('included_products', 'hidden', [
            'name' => 'included_products'
        ]);

        $fieldset->addField('included_categories', 'hidden', [
            'name' => 'included_categories'
        ]);
        $fieldset->addField('included_cmsBlocks', 'hidden', [
            'name' => 'included_cmsBlocks'
        ]);
        $fieldset->addField('included_cmsPages', 'hidden', [
            'name' => 'included_cmsPages'
        ]);

        $fieldset->addField('name', 'text', [
            'name'     => 'name',
            'label'    => $this->_getHelper()->__('Project Name'),
            'title'    => $this->_getHelper()->__('Project Name'),
            'required' => true,
            'disabled' => !$canEdit,
        ]);

        $teamValues = Mage::getModel('easytranslate/source_team')->toOptionArray();
        $note       = '';
        if (empty($teamValues)) {
            $note = $this->_getHelper()
                ->__('If no team is displayed, please check your API and EasyTranslate settings.');
        }
        $fieldset->addField('team', 'select', [
            'name'     => 'team',
            'label'    => $this->_getHelper()->__('Account'),
            'title'    => $this->_getHelper()->__('Account'),
            'required' => true,
            'values'   => $teamValues,
            'disabled' => !$canEdit,
            'note'     => $note,
        ]);

        if ($project && $project->getId()) {
            $fieldset->addField('status', 'label', [
                'label' => $this->_getHelper()->__('Status'),
            ]);

            $bold = false;
            if ($project->getData('status') === EasyTranslate_Connector_Model_Source_Status::PRICE_APPROVAL_REQUEST) {
                $bold = true;
            }
            $fieldset->addField('price', 'label', [
                'label' => $this->_getHelper()->__('Price'),
                'bold'  => $bold,
            ]);
        }

        $field    = $fieldset->addField('source_store_id', 'select', [
            'name'     => 'source_store_id',
            'label'    => $this->_getHelper()->__('Source Store View'),
            'title'    => $this->_getHelper()->__('Source Store View'),
            'required' => true,
            'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(),
            'disabled' => !$canEdit,
        ]);
        $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
        $field->setRenderer($renderer);

        $field = $fieldset->addField('target_stores', 'multiselect', [
            'name'     => 'target_stores[]',
            'label'    => $this->_getHelper()->__('Target Store Views'),
            'title'    => $this->_getHelper()->__('Target Store Views'),
            'required' => true,
            'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(),
            'disabled' => !$canEdit,
        ]);
        $field->setRenderer($renderer);

        $fieldset->addField('workflow', 'select', [
            'name'     => 'workflow',
            'label'    => $this->_getHelper()->__('Workflow'),
            'title'    => $this->_getHelper()->__('Workflow'),
            'required' => true,
            'values'   => Mage::getModel('easytranslate/source_workflow')->toOptionArray(),
            'disabled' => !$canEdit,
        ]);
        $fieldset->addField('automatic_import', 'select',
            [
                'label'    => $this->_getHelper()->__('Automatic Import'),
                'title'    => $this->_getHelper()->__('Automatic Import'),
                'name'     => 'automatic_import',
                'values'   => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
                'value'    => 1,
                'disabled' => !$canEdit,
            ]);

        if ($project instanceof EasyTranslate_Connector_Model_Project) {
            $values                        = $project->getData();
            $values['included_products']   = implode(',', $project->getProducts());
            $values['included_categories'] = implode(',', $project->getCategories());
            $values['included_cmsBlocks']  = implode(',', $project->getCmsBlocks());
            $values['included_cmsPages']   = implode(',', $project->getCmsPages());
            if (isset($values['price']) && $values['price'] === '0.0000') {
                $values['price'] = $this->_getHelper()->__('tbd');
            } elseif (isset($values['price'], $values['currency'])) {
                $currency        = Mage::app()->getLocale()->currency($values['currency']);
                $values['price'] = $currency->toCurrency($values['price']);
            }
            $statusLabel      = Mage::getModel('easytranslate/source_status')->getOptions()[$values['status']];
            $values['status'] = $this->_getHelper()->__($statusLabel);
            $form->setValues($values);
        }
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return $this->_getHelper()->__('General');
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return $this->_getHelper()->__('General');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
}
