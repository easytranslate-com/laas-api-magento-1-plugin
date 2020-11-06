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

    public function __construct()
    {
        $this->_config = Mage::getModel('easytranslate/config');
    }

    abstract protected function _getCollection(array $modelIds): Varien_Data_Collection_Db;

    public function getContent(array $modelIds): array
    {
        $content = [];
        $models  = $this->_getCollection($modelIds);
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
        foreach ($this->_attributeCodes as $attributeCode) {
            $value = $model->getData($attributeCode);
            if ($value === null || $value === '') {
                continue;
            }
            $keyParts      = [static::ENTITY_CODE, $model->getId(), $attributeCode];
            $key           = implode(self::KEY_SEPARATOR, $keyParts);
            $content[$key] = $value;
        }

        return $content;
    }
}
