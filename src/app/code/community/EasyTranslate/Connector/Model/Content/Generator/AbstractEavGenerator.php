<?php

declare(strict_types=1);

abstract class EasyTranslate_Connector_Model_Content_Generator_AbstractEavGenerator
    extends EasyTranslate_Connector_Model_Content_Generator_AbstractGenerator
{
    protected $_sortedAttributeCodes = [];

    protected function _getAttributeCodes(Mage_Core_Model_Abstract $model): array
    {
        $attributeSetId = (int)$model->getData('attribute_set_id');
        $resourceModel  = $model->getResource();
        if (!$attributeSetId || !$resourceModel instanceof Mage_Eav_Model_Entity_Abstract) {
            return parent::_getAttributeCodes($model);
        }

        if (!isset($this->_sortedAttributeCodes[$attributeSetId])) {
            $allSortedAttributes                          = $resourceModel
                ->loadAllAttributes($model)
                ->getSortedAttributes($attributeSetId);
            $allSortedAttributeCodes                      = array_keys($allSortedAttributes);
            $this->_sortedAttributeCodes[$attributeSetId] = array_intersect($allSortedAttributeCodes,
                $this->_attributeCodes);
        }

        return $this->_sortedAttributeCodes[$attributeSetId];
    }
}
