<?php

declare(strict_types=1);

class EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_CmsPages
    extends EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_AbstractEntity
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('cmsPages');
        $this->setDefaultSort('main_table.page_id');
        $this->setData('row_click_callback', $this->getJsObjectName() . '.easyTranslateRowClickCallback');
    }

    protected function _addColumnFilterToCollection($column
    ): EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_CmsPages {
        if (!$this->getCollection() || $column->getId() !== 'in_project') {
            return parent::_addColumnFilterToCollection($column);
        }

        $cmsPageIds  = $this->_getSelectedCmsPageIds();
        $filterValue = (int)$column->getFilter()->getValue();
        if ($filterValue === 1) {
            // user filtered by in_project "yes"
            $this->getCollection()->addFieldToFilter('main_table.page_id', ['in' => $cmsPageIds]);
        } elseif ($filterValue === 0 && !empty($cmsPageIds)) {
            // user filtered by in_project "no"
            $this->getCollection()->addFieldToFilter('main_table.page_id', ['nin' => $cmsPageIds]);
        }

        return $this;
    }

    protected function _prepareCollection(): EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_CmsPages
    {
        $this->setDefaultFilter(['in_project' => 1]);
        $collection = Mage::getModel('cms/page')->getCollection()->addFieldToSelect('page_id')
            ->addFieldToSelect('title')
            ->addFieldToSelect('identifier')
            ->addFieldToSelect('root_template')
            ->addFieldToSelect('is_active')
            ->addFieldToSelect('creation_time')
            ->addFieldToSelect('update_time');

        if ($this->_getProject() && !$this->_getProject()->canEditDetails()) {
            $selectedCmsPageIds = $this->_getSelectedCmsPageIds();
            $collection->addFieldToFilter('main_table.page_id', ['in' => $selectedCmsPageIds]);
        }

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns(): EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_CmsPages
    {
        if (!$this->_getProject() || $this->_getProject()->canEditDetails()) {
            $this->addColumn('in_project', [
                'header_css_class' => 'a-center',
                'inline_css'       => 'in-project',
                'type'             => 'checkbox',
                'name'             => 'in_project',
                'values'           => $this->_getSelectedCmsPageIds(),
                'align'            => 'center',
                'index'            => 'page_id'
            ]);
        }
        $this->addColumn('page_id', [
            'header'       => Mage::helper('cms')->__('ID'),
            'sortable'     => true,
            'width'        => '60',
            'index'        => 'page_id',
            'filter_index' => 'main_table.page_id'
        ]);
        $this->addColumn('page_title', [
            'header' => Mage::helper('cms')->__('Title'),
            'index'  => 'title'
        ]);
        $this->addColumn('page_identifier', [
            'header' => Mage::helper('cms')->__('URL Key'),
            'align'  => 'left',
            'index'  => 'identifier'
        ]);
        $this->addColumn('page_root_template', [
            'header'  => Mage::helper('cms')->__('Layout'),
            'index'   => 'root_template',
            'type'    => 'options',
            'options' => Mage::getSingleton('page/source_layout')->getOptions(),
        ]);
        $this->addColumn('page_store_id', [
            'header'     => Mage::helper('cms')->__('Store View'),
            'index'      => 'store_id',
            'type'       => 'store',
            'store_all'  => true,
            'store_view' => true,
            'sortable'   => false,
            'filter_condition_callback'
                         => [$this, '_filterStoreCondition'],
        ]);
        $this->addColumn('page_is_active', [
            'header'  => Mage::helper('cms')->__('Status'),
            'index'   => 'is_active',
            'type'    => 'options',
            'options' => Mage::getSingleton('cms/page')->getAvailableStatuses()
        ]);
        $this->addColumn('page_creation_time', [
            'header' => Mage::helper('cms')->__('Date Created'),
            'index'  => 'creation_time',
            'type'   => 'datetime',
        ]);

        $this->addColumn('page_update_time', [
            'header' => Mage::helper('cms')->__('Last Modified'),
            'index'  => 'update_time',
            'type'   => 'datetime',
        ]);

        return parent::_prepareColumns();
    }

    public function getGridUrl(): string
    {
        return $this->getUrl('*/*/cmsPagesGrid', ['_current' => true]);
    }

    protected function _getSelectedCmsPageIds(): array
    {
        $cmsPages = $this->getRequest()->getPost('included_cmsPages');
        if (is_null($cmsPages)) {
            if ($this->_getProject()) {
                return $this->_getProject()->getCmsPages();
            }

            return [];
        }

        return explode(',', $cmsPages);
    }

    public function getTabLabel(): string
    {
        return $this->_getHelper()->__('CMS Pages');
    }

    public function getTabTitle(): string
    {
        return $this->_getHelper()->__('CMS Pages');
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
