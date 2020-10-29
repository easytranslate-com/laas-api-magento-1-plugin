<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Project extends Mage_Core_Model_Abstract
{
    protected function _construct(): void
    {
        $this->_init('easytranslate/project');
    }

    public function getProducts(): array
    {
        if (!$this->getId()) {
            return [];
        }

        $products = $this->getData('products');
        if (is_null($products)) {
            $products = $this->getResource()->getProducts($this);
            $this->setData('products', $products);
        }

        return $products;
    }

    public function getCategories(): array
    {
        if (!$this->getId()) {
            return [];
        }

        $categories = $this->getData('categories');
        if (is_null($categories)) {
            $categories = $this->getResource()->getCategories($this);
            $this->setData('categories', $categories);
        }

        return $categories;
    }

    public function getCmsBlocks(): array
    {
        if (!$this->getId()) {
            return [];
        }

        $cmsBlocks = $this->getData('cmsBlocks');
        if (is_null($cmsBlocks)) {
            $cmsBlocks = $this->getResource()->getCmsBlocks($this);
            $this->setData('cmsBlocks', $cmsBlocks);
        }

        return $cmsBlocks;
    }

    public function canEditDetails(): bool
    {
        return !$this->getId() || $this->getData('status') === EasyTranslate_Connector_Model_Source_Status::OPEN;
    }
}
