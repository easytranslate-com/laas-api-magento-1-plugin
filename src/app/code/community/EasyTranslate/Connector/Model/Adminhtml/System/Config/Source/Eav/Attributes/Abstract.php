<?php

declare(strict_types=1);

abstract class EasyTranslate_Connector_Model_Adminhtml_System_Config_Source_Eav_Attributes_Abstract
{
    protected const EXCLUDED_ATTRIBUTES = [];

    abstract public function toOptionArray(): array;

    protected function _getEntityAttributes($entityTypeCode): Mage_Eav_Model_Resource_Entity_Attribute_Collection
    {
        $entityType = Mage::getSingleton('eav/config')->getEntityType($entityTypeCode);

        return Mage::getResourceModel('eav/entity_attribute_collection')
            ->addFieldToFilter('frontend_input', ['in' => ['text', 'textarea']])
            ->addFieldToFilter('attribute_code', ['nin' => static::EXCLUDED_ATTRIBUTES])
            ->setEntityTypeFilter($entityType)
            ->setOrder('frontend_label', Varien_Data_Collection_Db::SORT_ORDER_ASC);
    }

    public function _convertEntityAttributesToOptionArray(
        Mage_Eav_Model_Resource_Entity_Attribute_Collection $attributes
    ): array {
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
