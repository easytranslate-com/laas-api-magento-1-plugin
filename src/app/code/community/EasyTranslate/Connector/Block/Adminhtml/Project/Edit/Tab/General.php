<?php

declare(strict_types=1);

class EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _getHelper(): EasyTranslate_Connector_Helper_Data
    {
        return $this->helper('easytranslate');
    }

    protected function _prepareForm(): EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_General
    {
        $project = Mage::registry('current_project');
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

        $fieldset->addField('name', 'text', [
            'name'     => 'name',
            'label'    => $this->_getHelper()->__('Name'),
            'title'    => $this->_getHelper()->__('Name'),
            'required' => true,
        ]);

        $fieldset->addField('status', 'label', [
            'label' => $this->_getHelper()->__('Status'),
        ]);

        $fieldset->addField('price', 'label', [
            'label' => $this->_getHelper()->__('Price'),
        ]);

        $field    = $fieldset->addField('source_store_id', 'select', [
            'name'     => 'source_store_id',
            'label'    => $this->_getHelper()->__('Source Store View'),
            'title'    => $this->_getHelper()->__('Source Store View'),
            'required' => true,
            'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(),
            'disabled' => false,
        ]);
        $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
        $field->setRenderer($renderer);

        $field = $fieldset->addField('target_stores', 'multiselect', [
            'name'     => 'target_stores[]',
            'label'    => $this->_getHelper()->__('Target Store Views'),
            'title'    => $this->_getHelper()->__('Target Store Views'),
            'required' => true,
            'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(),
            'disabled' => false,
        ]);
        $field->setRenderer($renderer);

        if ($project) {
            $values                      = $project->getData();
            $values['included_products'] = implode(',', $project->getProducts());
            if (isset($values['price'], $values['currency'])) {
                $currency        = Mage::app()->getLocale()->currency($values['currency']);
                $values['price'] = $currency->toCurrency($values['price']);
            }
            $form->setValues($values);
        }
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel(): string
    {
        return $this->_getHelper()->__('General');
    }

    public function getTabTitle(): string
    {
        return $this->_getHelper()->__('General');
    }

    public function canShowTab(): bool
    {
        return true;
    }

    public function isHidden(): bool
    {
        return false;
    }
}
