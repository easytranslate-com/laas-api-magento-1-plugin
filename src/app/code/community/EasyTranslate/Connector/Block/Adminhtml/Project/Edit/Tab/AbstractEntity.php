<?php

declare(strict_types=1);

abstract class EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_AbstractEntity
    extends Mage_Adminhtml_Block_Widget_Grid implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function __construct()
    {
        parent::__construct();
        $this->setData('use_ajax', true);
    }

    public function getRowUrl($item): string
    {
        return '';
    }

    protected function _getHelper(): EasyTranslate_Connector_Helper_Data
    {
        return $this->helper('easytranslate');
    }

    protected function _getProject(): ?EasyTranslate_Connector_Model_Project
    {
        return Mage::registry('current_project');
    }

    public function canShowTab(): bool
    {
        return true;
    }

    public function isHidden(): bool
    {
        return $this->_getProject() === null || !$this->_getProject()->getId();
    }
}
