<?php

class EasyTranslate_Connector_Model_Adminhtml_System_Config_Source_Eav_Attributes_Category
    extends EasyTranslate_Connector_Model_Adminhtml_System_Config_Source_Eav_Attributes_Abstract
{
    const EXCLUDED_ATTRIBUTES = ['custom_layout_update', 'url_path', 'meta_keywords', 'filter_price_range'];

    /**
     * @return mixed[]
     */
    public function toOptionArray()
    {
        $attributes = $this->_getEntityAttributes(Mage_Catalog_Model_Category::ENTITY);
        $attributes->addFieldToFilter('is_global', false);

        return $this->_convertEntityAttributesToOptionArray($attributes);
    }
}
