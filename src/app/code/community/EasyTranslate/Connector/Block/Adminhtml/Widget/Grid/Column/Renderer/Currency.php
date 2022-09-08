<?php

class EasyTranslate_Connector_Block_Adminhtml_Widget_Grid_Column_Renderer_Currency
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency
{
    /**
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $data = (string)$row->getData($this->getColumn()->getIndex());
        if ($data && $data === '0.0000') {
            return $this->helper('easytranslate')->__('tbd');
        }

        return parent::render($row);
    }
}
