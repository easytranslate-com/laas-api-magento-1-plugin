<?php

declare(strict_types=1);

abstract class EasyTranslate_Connector_Model_Content_Generator_AbstractGenerator
{
    public const ENTITY_CODE = '';

    public const KEY_SEPARATOR = '###';

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

    abstract protected function _getCollection(array $modelIds, int $storeId): Varien_Data_Collection_Db;

    public function getContent(array $modelIds, int $storeId): array
    {
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

    protected function _getSingleContent(Mage_Core_Model_Abstract $model): array
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

    protected function _getAttributeCodes(Mage_Core_Model_Abstract $model): array
    {
        return $this->_attributeCodes;
    }
}
