<?php

abstract class EasyTranslate_Connector_Model_Content_Generator_AbstractGenerator
{
    const ENTITY_CODE = '';

    const KEY_SEPARATOR = '###';

    /**
     * @var EasyTranslate_Connector_Model_Config
     */
    protected $_config;

    /**
     * @var array
     */
    protected $_attributeCodes;

    /**
     * @var string
     */
    protected $_idField = 'entity_id';

    public function __construct()
    {
        $this->_config = Mage::getModel('easytranslate/config');
    }

    /**
     * @param int $storeId
     * @return \Varien_Data_Collection_Db
     */
    abstract protected function _getCollection(array $modelIds, $storeId);

    /**
     * @param int $storeId
     * @return mixed[]
     */
    public function getContent(array $modelIds, $storeId)
    {
        $storeId = (int) $storeId;
        $content = [];
        $models  = $this->_getCollection($modelIds, $storeId);
        foreach ($models as $model) {
            $singleContent = $this->_getSingleContent($model);
            foreach ($singleContent as $key => $value) {
                $content[$key] = $value;
            }
        }

        return $content;
    }

    /**
     * @return mixed[]
     */
    protected function _getSingleContent(Mage_Core_Model_Abstract $model)
    {
        $content = [];
        foreach ($this->_getAttributeCodes($model) as $attributeCode) {
            $value = $model->getData($attributeCode);
            if ($value === null || $value === '') {
                continue;
            }
            $keyParts      = [static::ENTITY_CODE, $model->getData($this->_idField), $attributeCode];
            $key           = implode(self::KEY_SEPARATOR, $keyParts);
            $content[$key] = $value;
        }

        return $content;
    }

    /**
     * @return mixed[]
     */
    protected function _getAttributeCodes(Mage_Core_Model_Abstract $model)
    {
        return $this->_attributeCodes;
    }
}
