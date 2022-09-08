<?php

use EasyTranslate\Api\Configuration as ApiConfiguration;

class EasyTranslate_Connector_Model_Config
{
    const XML_PATH_API_ENVIRONMENT = 'easytranslate/api/environment';
    const XML_PATH_API_CLIENT_ID = 'easytranslate/api/client_id';
    const XML_PATH_API_CLIENT_SECRET = 'easytranslate/api/client_secret';
    const XML_PATH_API_USERNAME = 'easytranslate/api/username';
    const XML_PATH_API_PASSWORD = 'easytranslate/api/password';
    const XML_PATH_PRODUCTS_ATTRIBUTES = 'easytranslate/products/attributes';
    const XML_PATH_CATEGORIES_ATTRIBUTES = 'easytranslate/categories/attributes';
    const XML_PATH_CMS_BLOCKS_ATTRIBUTES = 'easytranslate/cms_blocks/attributes';
    const XML_PATH_CMS_PAGES_ATTRIBUTES = 'easytranslate/cms_pages/attributes';

    /**
     * @return string
     */
    public function getApiEnvironment($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_API_ENVIRONMENT, $store);
    }

    /**
     * @return string
     */
    public function getApiClientId($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_API_CLIENT_ID, $store);
    }

    /**
     * @return string
     */
    public function getApiClientSecret($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_API_CLIENT_SECRET, $store);
    }

    /**
     * @return string
     */
    public function getApiUsername($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_API_USERNAME, $store);
    }

    /**
     * @return string
     */
    public function getApiPassword($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_API_PASSWORD, $store);
    }

    /**
     * @return mixed[]
     */
    public function getProductsAttributes($store = null)
    {
        $rawAttributes = Mage::getStoreConfig(self::XML_PATH_PRODUCTS_ATTRIBUTES, $store);

        return $this->_explodeRawAttributes($rawAttributes);
    }

    /**
     * @return mixed[]
     */
    public function getCategoriesAttributes($store = null)
    {
        $rawAttributes = Mage::getStoreConfig(self::XML_PATH_CATEGORIES_ATTRIBUTES, $store);

        return $this->_explodeRawAttributes($rawAttributes);
    }

    /**
     * @return mixed[]
     */
    public function getCmsBlocksAttributes($store = null)
    {
        $rawAttributes = Mage::getStoreConfig(self::XML_PATH_CMS_BLOCKS_ATTRIBUTES, $store);

        return $this->_explodeRawAttributes($rawAttributes);
    }

    /**
     * @return mixed[]
     */
    public function getCmsPagesAttributes($store = null)
    {
        $rawAttributes = Mage::getStoreConfig(self::XML_PATH_CMS_PAGES_ATTRIBUTES, $store);

        return $this->_explodeRawAttributes($rawAttributes);
    }

    /**
     * @return mixed[]
     */
    protected function _explodeRawAttributes($rawAttributes)
    {
        if ($rawAttributes === null || $rawAttributes === '') {
            return [];
        }

        return explode(',', $rawAttributes);
    }

    /**
     * @return ApiConfiguration
     */
    public function getApiConfiguration()
    {
        return new ApiConfiguration(
            $this->getApiEnvironment(),
            $this->getApiClientId(),
            $this->getApiClientSecret(),
            $this->getApiUsername(),
            $this->getApiPassword()
        );
    }
}
