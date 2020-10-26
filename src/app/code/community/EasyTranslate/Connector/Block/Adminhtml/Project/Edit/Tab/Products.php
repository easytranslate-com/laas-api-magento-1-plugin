<?php

declare(strict_types=1);

class EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_Products
    extends EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_AbstractEntity
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('products');
        $this->setDefaultSort('entity_id');
        $this->setData('row_click_callback', $this->getJsObjectName() . '.easyTranslateRowClickCallback');
    }

    protected function _addColumnFilterToCollection(
        $column
    ): EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_Products {
        if (!$this->getCollection() || $column->getId() !== 'in_project') {
            return parent::_addColumnFilterToCollection($column);
        }

        $productIds  = $this->_getSelectedProductIds();
        $filterValue = (int)$column->getFilter()->getValue();
        if ($filterValue === 1) {
            // user filtered by in_project "yes"
            $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
        } elseif ($filterValue === 0 && !empty($productIds)) {
            // user filtered by in_project "no"
            $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
        }

        return $this;
    }

    protected function _prepareCollection(): EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_Products
    {
        $this->setDefaultFilter(['in_project' => 1]);
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('price');

        if ($this->_getProject()) {
            $collection->addStoreFilter($this->_getProject()->getData('source_store_id'));
            if (!$this->_getProject()->canEditDetails()) {
                $productIds = $this->_getSelectedProductIds();
                if (!empty($productIds)) {
                    $collection->addFieldToFilter('entity_id', ['in' => $productIds]);
                }
            }
        }

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns(): EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_Products
    {
        if (!$this->_getProject() || $this->_getProject()->canEditDetails()) {
            $this->addColumn('in_project', [
                'header_css_class' => 'a-center',
                'inline_css'       => 'in-project',
                'type'             => 'checkbox',
                'name'             => 'in_project',
                'values'           => $this->_getSelectedProductIds(),
                'align'            => 'center',
                'index'            => 'entity_id'
            ]);
        }
        $this->addColumn('entity_id', [
            'header'   => Mage::helper('catalog')->__('ID'),
            'sortable' => true,
            'width'    => '60',
            'index'    => 'entity_id'
        ]);
        $this->addColumn('product_name', [
            'header' => Mage::helper('catalog')->__('Name'),
            'index'  => 'name'
        ]);
        $this->addColumn('sku', [
            'header' => Mage::helper('catalog')->__('SKU'),
            'width'  => '80',
            'index'  => 'sku'
        ]);
        $this->addColumn('price', [
            'header'        => Mage::helper('catalog')->__('Price'),
            'type'          => 'currency',
            'width'         => '1',
            'currency_code' => (string)Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'         => 'price'
        ]);

        return parent::_prepareColumns();
    }

    public function getGridUrl(): string
    {
        return $this->getUrl('*/*/productGrid', ['_current' => true]);
    }

    protected function _getSelectedProductIds(): array
    {
        $products = $this->getRequest()->getPost('included_products');
        if (is_null($products)) {
            if ($this->_getProject()) {
                return $this->_getProject()->getProducts();
            }

            return [];
        }

        return explode(',', $products);
    }

    public function getTabLabel(): string
    {
        return $this->_getHelper()->__('Products');
    }

    public function getTabTitle(): string
    {
        return $this->_getHelper()->__('Products');
    }
}
