<?php

class EasyTranslate_Connector_Model_Adminhtml_System_Config_Source_Eav_Attributes_Product
    extends EasyTranslate_Connector_Model_Adminhtml_System_Config_Source_Eav_Attributes_Abstract
{
    const EXCLUDED_ATTRIBUTES = ['custom_layout_update', 'url_path', 'meta_keyword'];

    /**
     * @return mixed[]
     */
    public function toOptionArray()
    {
        $attributes = $this->_getEntityAttributes(Mage_Catalog_Model_Product::ENTITY);
        $attributes->addFieldToFilter('is_global', false);

        return $this->_convertEntityAttributesToOptionArray($attributes);
    }
}
