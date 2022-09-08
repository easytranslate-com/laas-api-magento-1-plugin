<?php

use EasyTranslate\ProjectInterface;

class EasyTranslate_Connector_Model_Bridge_Project implements ProjectInterface
{
    /**
     * @var EasyTranslate_Connector_Model_Project
     */
    protected $_magentoProject;

    /**
     * @var EasyTranslate_Connector_Model_Locale_SourceMapper
     */
    protected $_sourceLocaleMapper;

    /**
     * @var EasyTranslate_Connector_Model_Locale_TargetMapper
     */
    protected $_targetLocaleMapper;

    public function __construct(EasyTranslate_Connector_Model_Project $magentoProject)
    {
        $this->_magentoProject     = $magentoProject;
        $this->_sourceLocaleMapper = Mage::getModel('easytranslate/locale_sourceMapper');
        $this->_targetLocaleMapper = Mage::getModel('easytranslate/locale_targetMapper');
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_magentoProject->getData('external_id');
    }

    /**
     * @return string
     */
    public function getTeam()
    {
        return $this->_magentoProject->getData('team');
    }

    /**
     * @return string
     */
    public function getSourceLanguage()
    {
        $sourceStoreId = $this->_magentoProject->getData('source_store_id');
        $sourceLocale  = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE, $sourceStoreId);

        return $this->_sourceLocaleMapper->mapMagentoCodeToExternalCode($sourceLocale);
    }

    /**
     * @return mixed[]
     */
    public function getTargetLanguages()
    {
        $targetLanguages = [];
        $targetStores    = $this->_magentoProject->getData('target_stores');
        foreach ($targetStores as $targetStore) {
            $targetLocale      = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE, $targetStore);
            $targetLanguages[] = $this->_targetLocaleMapper->mapMagentoCodeToExternalCode($targetLocale);
        }

        return array_values(array_unique($targetLanguages));
    }

    /**
     * @return string
     */
    public function getCallbackUrl()
    {
        return Mage::getModel('easytranslate/callback_linkGenerator')->generateLink($this->_magentoProject);
    }

    /**
     * @return mixed[]
     */
    public function getContent()
    {
        $storeId           = (int)$this->_magentoProject->getData('source_store_id');
        $cmsBlocksContent  = $this->_getCmsBlocksContent($storeId);
        $cmsPagesContent   = $this->_getCmsPagesContent($storeId);
        $categoriesContent = $this->_getCategoriesContent($storeId);
        $productsContent   = $this->_getProductsContent($storeId);

        return array_merge($cmsBlocksContent, $cmsPagesContent, $categoriesContent, $productsContent);
    }

    /**
     * @param int $storeId
     * @return mixed[]
     */
    protected function _getCmsBlocksContent($storeId)
    {
        $storeId = (int) $storeId;
        $cmsBlockIds = $this->_magentoProject->getCmsBlocks();

        return Mage::getModel('easytranslate/content_generator_cmsBlock')->getContent($cmsBlockIds, $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed[]
     */
    protected function _getCmsPagesContent($storeId)
    {
        $storeId = (int) $storeId;
        $cmsPageIds = $this->_magentoProject->getCmsPages();

        return Mage::getModel('easytranslate/content_generator_cmsPage')->getContent($cmsPageIds, $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed[]
     */
    protected function _getCategoriesContent($storeId)
    {
        $storeId = (int) $storeId;
        $categoryIds = $this->_magentoProject->getCategories();

        return Mage::getModel('easytranslate/content_generator_category')->getContent($categoryIds, $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed[]
     */
    protected function _getProductsContent($storeId)
    {
        $storeId = (int) $storeId;
        $productIds = $this->_magentoProject->getProducts();

        return Mage::getModel('easytranslate/content_generator_product')->getContent($productIds, $storeId);
    }

    /**
     * @return string
     */
    public function getWorkflow()
    {
        return $this->_magentoProject->getData('workflow');
    }

    /**
     * @return string|null
     */
    public function getFolderId()
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getFolderName()
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->_magentoProject->getData('name');
    }

    /**
     * @return mixed[]
     */
    public function getTasks()
    {
        return $this->_magentoProject->getTasks();
    }

    /**
     * @return float|null
     */
    public function getPrice()
    {
        return (float)$this->_magentoProject->getData('price');
    }

    /**
     * @return string|null
     */
    public function getCurrency()
    {
        return $this->_magentoProject->getData('currency');
    }
}
