<?php

declare(strict_types=1);

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

    public function getId(): string
    {
        return $this->_magentoProject->getData('external_id');
    }

    public function getTeam(): string
    {
        return $this->_magentoProject->getData('team');
    }

    public function getSourceLanguage(): string
    {
        $sourceStoreId = $this->_magentoProject->getData('source_store_id');
        $sourceLocale  = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE, $sourceStoreId);

        return $this->_sourceLocaleMapper->mapMagentoCodeToExternalCode($sourceLocale);
    }

    public function getTargetLanguages(): array
    {
        $targetLanguages = [];
        $targetStores    = $this->_magentoProject->getData('target_stores');
        foreach ($targetStores as $targetStore) {
            $targetLocale      = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE, $targetStore);
            $targetLanguages[] = $this->_targetLocaleMapper->mapMagentoCodeToExternalCode($targetLocale);
        }

        return array_values(array_unique($targetLanguages));
    }

    public function getCallbackUrl(): string
    {
        return Mage::getModel('easytranslate/callback_linkGenerator')->generateLink($this->_magentoProject);
    }

    public function getContent(): array
    {
        $storeId           = (int)$this->_magentoProject->getData('source_store_id');
        $cmsBlocksContent  = $this->_getCmsBlocksContent($storeId);
        $cmsPagesContent   = $this->_getCmsPagesContent($storeId);
        $categoriesContent = $this->_getCategoriesContent($storeId);
        $productsContent   = $this->_getProductsContent($storeId);

        return array_merge($cmsBlocksContent, $cmsPagesContent, $categoriesContent, $productsContent);
    }

    protected function _getCmsBlocksContent(int $storeId): array
    {
        $cmsBlockIds = $this->_magentoProject->getCmsBlocks();

        return Mage::getModel('easytranslate/content_generator_cmsBlock')->getContent($cmsBlockIds, $storeId);
    }

    protected function _getCmsPagesContent(int $storeId): array
    {
        $cmsPageIds = $this->_magentoProject->getCmsPages();

        return Mage::getModel('easytranslate/content_generator_cmsPage')->getContent($cmsPageIds, $storeId);
    }

    protected function _getCategoriesContent(int $storeId): array
    {
        $categoryIds = $this->_magentoProject->getCategories();

        return Mage::getModel('easytranslate/content_generator_category')->getContent($categoryIds, $storeId);
    }

    protected function _getProductsContent(int $storeId): array
    {
        $productIds = $this->_magentoProject->getProducts();

        return Mage::getModel('easytranslate/content_generator_product')->getContent($productIds, $storeId);
    }

    public function getWorkflow(): string
    {
        return $this->_magentoProject->getData('workflow');
    }

    public function getFolderId(): ?string
    {
        return null;
    }

    public function getFolderName(): ?string
    {
        return null;
    }

    public function getName(): ?string
    {
        return $this->_magentoProject->getData('name');
    }

    public function getTasks(): array
    {
        return $this->_magentoProject->getTasks();
    }

    public function getPrice(): ?float
    {
        return (float)$this->_magentoProject->getData('price');
    }

    public function getCurrency(): ?string
    {
        return $this->_magentoProject->getData('currency');
    }
}
