<?php

abstract class EasyTranslate_Connector_Model_Adminhtml_System_Config_Source_Eav_Attributes_Abstract
{
    const EXCLUDED_ATTRIBUTES = [];

    /**
     * @return mixed[]
     */
    abstract public function toOptionArray();

    /**
     * @return \Mage_Eav_Model_Resource_Entity_Attribute_Collection
     */
    protected function _getEntityAttributes($entityTypeCode)
    {
        $entityType = Mage::getSingleton('eav/config')->getEntityType($entityTypeCode);

        return Mage::getResourceModel('eav/entity_attribute_collection')
            ->addFieldToFilter('frontend_input', ['in' => ['text', 'textarea']])
            ->addFieldToFilter('attribute_code', ['nin' => static::EXCLUDED_ATTRIBUTES])
            ->setEntityTypeFilter($entityType)
            ->setOrder('frontend_label', Varien_Data_Collection_Db::SORT_ORDER_ASC);
    }

    /**
     * @return mixed[]
     */
    public function _convertEntityAttributesToOptionArray(
        Mage_Eav_Model_Resource_Entity_Attribute_Collection $attributes
    ) {
        $options = [];
        /** @var Mage_Catalog_Helper_Data $helper */
        $helper = Mage::helper('catalog');
        foreach ($attributes as $attribute) {
            $options[] = [
                'value' => $attribute->getAttributeCode(),
                'label' => $helper->__($attribute->getData('frontend_label'))
            ];
        }

        return $options;
    }
}
