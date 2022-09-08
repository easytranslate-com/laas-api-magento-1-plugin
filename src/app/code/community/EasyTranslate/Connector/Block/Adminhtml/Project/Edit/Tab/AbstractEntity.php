<?php

abstract class EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_AbstractEntity
    extends Mage_Adminhtml_Block_Widget_Grid implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function __construct()
    {
        parent::__construct();
        $this->setData('use_ajax', true);
    }

    /**
     * @return string
     */
    public function getRowUrl($item)
    {
        return '';
    }

    /**
     * @return \EasyTranslate_Connector_Helper_Data
     */
    protected function _getHelper()
    {
        return $this->helper('easytranslate');
    }

    /**
     * @return \EasyTranslate_Connector_Model_Project|null
     */
    protected function _getProject()
    {
        return Mage::registry('current_project');
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
        return $this->_getProject() === null || !$this->_getProject()->getId();
    }

    /**
     * @return \EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_AbstractEntity
     */
    protected function _afterLoadCollection()
    {
        foreach ($this->_collection as $item) {
            $translatedStores = $item->getData('translated_stores');
            if (empty($translatedStores)) {
                continue;
            }
            $item->setData('translated_stores', explode(',', $translatedStores));
        }

        return parent::_afterLoadCollection();
    }
}
