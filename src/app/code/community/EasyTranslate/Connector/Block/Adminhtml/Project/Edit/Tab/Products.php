<?php

class EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_Products
    extends EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_AbstractEntity
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('products');
        $this->setDefaultSort('entity_id');
        $this->setData('row_click_callback', $this->getJsObjectName() . '.easyTranslateRowClickCallback');
        $this->setData('checkbox_check_callback', $this->getJsObjectName() . '.easyTranslateCheckboxCheckCallback');
    }

    /**
     * @return \EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_Products
     */
    protected function _addColumnFilterToCollection(
        $column
    ) {
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

    /**
     * @return \EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_Products
     */
    protected function _prepareCollection()
    {
        $this->setDefaultFilter(['in_project' => 1]);
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('price');

        if ($this->_getProject()) {
            $collection->addStoreFilter($this->_getProject()->getData('source_store_id'));
            if ($this->_getProject()->canEditDetails()) {
                // join stores in which products have already been added to a project / translated
                $projectProductTable     = $collection->getTable('easytranslate/project_product');
                $projectTargetStoreTable = $collection->getTable('easytranslate/project_target_store');
                $collection->getSelect()->joinLeft(
                    ['etpp' => $projectProductTable],
                    'etpp.product_id=e.entity_id',
                    ['project_ids' => 'GROUP_CONCAT(DISTINCT etpp.project_id)']
                );
                $collection->getSelect()->joinLeft(
                    ['etpts' => $projectTargetStoreTable],
                    'etpts.project_id=etpp.project_id',
                    ['translated_stores' => 'GROUP_CONCAT(DISTINCT target_store_id)']
                );
                $collection->groupByAttribute('entity_id');
            } else {
                $productIds = $this->_getSelectedProductIds();
                $collection->addFieldToFilter('entity_id', ['in' => $productIds]);
            }
        }

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return \EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_Products
     */
    protected function _prepareColumns()
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
        $this->addColumn('product_entity_id', [
            'header'   => Mage::helper('catalog')->__('ID'),
            'sortable' => true,
            'width'    => '60',
            'index'    => 'entity_id'
        ]);
        $this->addColumn('product_name', [
            'header' => Mage::helper('catalog')->__('Name'),
            'index'  => 'name'
        ]);
        $this->addColumn('product_sku', [
            'header' => Mage::helper('catalog')->__('SKU'),
            'width'  => '80',
            'index'  => 'sku'
        ]);
        $this->addColumn('product_price', [
            'header'        => Mage::helper('catalog')->__('Price'),
            'type'          => 'currency',
            'width'         => '1',
            'currency_code' => (string)Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'         => 'price'
        ]);
        if (!$this->_getProject() || $this->_getProject()->canEditDetails()) {
            $this->addColumn('translated_stores',
                [
                    'header'                    => $this->__('Already Translated In'),
                    'width'                     => '250px',
                    'index'                     => 'translated_stores',
                    'type'                      => 'store',
                    'store_view'                => true,
                    'sortable'                  => false,
                    'filter_condition_callback' => [$this, '_filterTranslatedCondition'],
                ]);
        }

        return parent::_prepareColumns();
    }

    /**
     * @return void
     */
    protected function _filterTranslatedCondition(
        Mage_Catalog_Model_Resource_Product_Collection $collection,
        Mage_Adminhtml_Block_Widget_Grid_Column $column
    ) {
        $value = $column->getFilter()->getValue();
        if ($value) {
            $collection->getSelect()->where('etpts.target_store_id=?', $value);
        }
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/productGrid', ['_current' => true]);
    }

    /**
     * @return mixed[]
     */
    protected function _getSelectedProductIds()
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

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return $this->_getHelper()->__('Products');
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return $this->_getHelper()->__('Products');
    }
}
