<?php

class EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_Categories
    extends EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_AbstractEntity
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('categories');
        $this->setDefaultSort('entity_id');
        $this->setData('row_click_callback', $this->getJsObjectName() . '.easyTranslateRowClickCallback');
        $this->setData('checkbox_check_callback', $this->getJsObjectName() . '.easyTranslateCheckboxCheckCallback');
    }

    /**
     * @return \EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_Categories
     */
    protected function _addColumnFilterToCollection(
        $column
    ) {
        if (!$this->getCollection() || $column->getId() !== 'in_project') {
            return parent::_addColumnFilterToCollection($column);
        }

        $categoryIds = $this->_getSelectedCategoryIds();
        $filterValue = (int)$column->getFilter()->getValue();
        if ($filterValue === 1) {
            // user filtered by in_project "yes"
            $this->getCollection()->addFieldToFilter('entity_id', ['in' => $categoryIds]);
        } elseif ($filterValue === 0 && !empty($categoryIds)) {
            // user filtered by in_project "no"
            $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $categoryIds]);
        }

        return $this;
    }

    /**
     * @return \EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_Categories
     */
    protected function _prepareCollection()
    {
        $this->setDefaultFilter(['in_project' => 1]);
        $collection = Mage::getResourceModel('easytranslate/catalog_category_collection')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('url_key')
            ->addAttributeToFilter('level', ['gt' => 1]);

        if ($this->_getProject()) {
            $collection->setStore($this->_getProject()->getData('source_store_id'));
            if ($this->_getProject()->canEditDetails()) {
                // join stores in which products have already been added to a project / translated
                $projectCategoryTable    = $collection->getTable('easytranslate/project_category');
                $projectTargetStoreTable = $collection->getTable('easytranslate/project_target_store');
                $collection->getSelect()->joinLeft(
                    ['etpc' => $projectCategoryTable],
                    'etpc.category_id=e.entity_id',
                    ['project_ids' => 'GROUP_CONCAT(DISTINCT etpc.project_id)']
                );
                $collection->getSelect()->joinLeft(
                    ['etpts' => $projectTargetStoreTable],
                    'etpts.project_id=etpc.project_id',
                    ['translated_stores' => 'GROUP_CONCAT(DISTINCT target_store_id)']
                );
                $collection->groupByAttribute('entity_id');
            } else {
                $categoryIds = $this->_getSelectedCategoryIds();
                $collection->addFieldToFilter('entity_id', ['in' => $categoryIds]);
            }
        }

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return mixed[]
     */
    public function getMultipleRows($item)
    {
        return [];
    }

    /**
     * @return \EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_Categories
     */
    protected function _prepareColumns()
    {
        if (!$this->_getProject() || $this->_getProject()->canEditDetails()) {
            $this->addColumn('in_project', [
                'header_css_class' => 'a-center',
                'inline_css'       => 'in-project',
                'type'             => 'checkbox',
                'name'             => 'in_project',
                'values'           => $this->_getSelectedCategoryIds(),
                'align'            => 'center',
                'index'            => 'entity_id'
            ]);
        }
        $this->addColumn('category_entity_id', [
            'header'   => Mage::helper('catalog')->__('ID'),
            'sortable' => true,
            'width'    => '60',
            'index'    => 'entity_id'
        ]);
        $this->addColumn('category_name', [
            'header' => Mage::helper('catalog')->__('Name'),
            'index'  => 'name'
        ]);
        $this->addColumn('category_url_key', [
            'header' => Mage::helper('catalog')->__('URL Key'),
            'index'  => 'url_key'
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
        Mage_Catalog_Model_Resource_Category_Collection $collection,
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
        return $this->getUrl('*/*/categoryGrid', ['_current' => true]);
    }

    /**
     * @return mixed[]
     */
    protected function _getSelectedCategoryIds()
    {
        $categories = $this->getRequest()->getPost('included_categories');
        if (is_null($categories)) {
            if ($this->_getProject()) {
                return $this->_getProject()->getCategories();
            }

            return [];
        }

        return explode(',', $categories);
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return $this->_getHelper()->__('Categories');
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return $this->_getHelper()->__('Categories');
    }
}
