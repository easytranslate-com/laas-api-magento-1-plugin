<?php

class EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('easytranslate_project_tabs');
        $this->setDestElementId('edit_form');
        $this->setData('title', Mage::helper('easytranslate')->__('Project Information'));
    }
}
