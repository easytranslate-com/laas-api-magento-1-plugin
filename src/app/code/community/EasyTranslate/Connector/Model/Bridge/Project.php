<?php

declare(strict_types=1);

use EasyTranslate\ProjectInterface;

class EasyTranslate_Connector_Model_Bridge_Project implements ProjectInterface
{
    /**
     * @var EasyTranslate_Connector_Model_Project
     */
    protected $_magentoProject;

    public function __construct(EasyTranslate_Connector_Model_Project $magentoProject)
    {
        $this->_magentoProject = $magentoProject;
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
        // TODO generate source language from source store view - we probably need a fixed mapping form magento language codes to ET codes
        return 'en';
    }

    public function getTargetLanguages(): array
    {
        // TODO generate target languages from target stores
        return ['da'];
    }

    public function getCallbackUrl(): string
    {
        return Mage::getModel('easytranslate/callback_linkGenerator')->generateLink($this->_magentoProject);
    }

    public function getContent(): array
    {
        $cmsBlocksContent  = $this->_getCmsBlocksContent();
        $cmsPagesContent   = $this->_getCmsPagesContent();
        $categoriesContent = $this->_getCategoriesContent();
        $productsContent   = $this->_getProductsContent();

        return array_merge($cmsBlocksContent, $cmsPagesContent, $categoriesContent, $productsContent);
    }

    protected function _getCmsBlocksContent(): array
    {
        $cmsBlockIds = $this->_magentoProject->getCmsBlocks();

        return Mage::getModel('easytranslate/content_generator_cmsBlock')->getContent($cmsBlockIds);
    }

    protected function _getCmsPagesContent(): array
    {
        $cmsPageIds = $this->_magentoProject->getCmsPages();

        return Mage::getModel('easytranslate/content_generator_cmsPage')->getContent($cmsPageIds);
    }

    protected function _getCategoriesContent(): array
    {
        $categoryIds = $this->_magentoProject->getCategories();

        return Mage::getModel('easytranslate/content_generator_category')->getContent($categoryIds);
    }

    protected function _getProductsContent(): array
    {
        $productIds = $this->_magentoProject->getProducts();

        return Mage::getModel('easytranslate/content_generator_product')->getContent($productIds);
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
}
