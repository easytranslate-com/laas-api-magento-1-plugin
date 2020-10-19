<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Adminhtml_System_Config_Source_Eav_Attributes_Category
    extends EasyTranslate_Connector_Model_Adminhtml_System_Config_Source_Eav_Attributes_Abstract
{
    protected const EXCLUDED_ATTRIBUTES = ['custom_layout_update', 'url_path', 'meta_keywords', 'filter_price_range'];

    public function toOptionArray(): array
    {
        $attributes = $this->_getEntityAttributes(Mage_Catalog_Model_Category::ENTITY);
        $attributes->addFieldToFilter('is_global', false);

        return $this->_convertEntityAttributesToOptionArray($attributes);
    }
}
