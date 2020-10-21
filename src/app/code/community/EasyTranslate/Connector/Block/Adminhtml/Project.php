<?php

declare(strict_types=1);

class EasyTranslate_Connector_Block_Adminhtml_Project extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup     = 'easytranslate';
        $this->_controller     = 'adminhtml_project';
        $this->_headerText     = $this->__('EasyTranslate Projects');
        $this->_addButtonLabel = $this->__('Add Project');
        parent::__construct();
    }
}
