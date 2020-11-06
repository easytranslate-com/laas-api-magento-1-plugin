<?php

declare(strict_types=1);

use EasyTranslate\Api\Configuration as ApiConfiguration;

class EasyTranslate_Connector_Model_Config
{
    protected const XML_PATH_API_ENVIRONMENT = 'easytranslate/api/environment';
    protected const XML_PATH_API_CLIENT_ID = 'easytranslate/api/client_id';
    protected const XML_PATH_API_CLIENT_SECRET = 'easytranslate/api/client_secret';
    protected const XML_PATH_API_USERNAME = 'easytranslate/api/username';
    protected const XML_PATH_API_PASSWORD = 'easytranslate/api/password';
    protected const XML_PATH_PRODUCTS_ATTRIBUTES = 'easytranslate/products/attributes';
    protected const XML_PATH_CATEGORIES_ATTRIBUTES = 'easytranslate/categories/attributes';
    protected const XML_PATH_CMS_BLOCKS_ATTRIBUTES = 'easytranslate/cms_blocks/attributes';
    protected const XML_PATH_CMS_PAGES_ATTRIBUTES = 'easytranslate/cms_pages/attributes';

    public function getApiEnvironment($store = null): string
    {
        return Mage::getStoreConfig(self::XML_PATH_API_ENVIRONMENT, $store);
    }

    public function getApiClientId($store = null): string
    {
        return Mage::getStoreConfig(self::XML_PATH_API_CLIENT_ID, $store);
    }

    public function getApiClientSecret($store = null): string
    {
        return Mage::getStoreConfig(self::XML_PATH_API_CLIENT_SECRET, $store);
    }

    public function getApiUsername($store = null): string
    {
        return Mage::getStoreConfig(self::XML_PATH_API_USERNAME, $store);
    }

    public function getApiPassword($store = null): string
    {
        return Mage::getStoreConfig(self::XML_PATH_API_PASSWORD, $store);
    }

    public function getProductsAttributes($store = null): array
    {
        $rawAttributes = Mage::getStoreConfig(self::XML_PATH_PRODUCTS_ATTRIBUTES, $store);

        return $this->_explodeRawAttributes($rawAttributes);
    }

    public function getCategoriesAttributes($store = null): array
    {
        $rawAttributes = Mage::getStoreConfig(self::XML_PATH_CATEGORIES_ATTRIBUTES, $store);

        return $this->_explodeRawAttributes($rawAttributes);
    }

    public function getCmsBlocksAttributes($store = null): array
    {
        $rawAttributes = Mage::getStoreConfig(self::XML_PATH_CMS_BLOCKS_ATTRIBUTES, $store);

        return $this->_explodeRawAttributes($rawAttributes);
    }

    public function getCmsPagesAttributes($store = null): array
    {
        $rawAttributes = Mage::getStoreConfig(self::XML_PATH_CMS_PAGES_ATTRIBUTES, $store);

        return $this->_explodeRawAttributes($rawAttributes);
    }

    protected function _explodeRawAttributes($rawAttributes): array
    {
        if ($rawAttributes === null || $rawAttributes === '') {
            return [];
        }

        return explode(',', $rawAttributes);
    }

    public function getApiConfiguration(): ApiConfiguration
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
