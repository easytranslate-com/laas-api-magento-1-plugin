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
        $this->setData('checkbox_check_callback', $this->getJsObjectName() . '.easyTranslateCheckboxCheckCallback');
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
        $collection = Mage::getResourceModel('easytranslate/cms_page_collection');

        if ($this->_getProject()) {
            $collection->addStoreFilter($this->_getProject()->getData('source_store_id'));
            if ($this->_getProject()->canEditDetails()) {
                // join stores in which pages have already been added to a project / translated
                $projectCmsPageTable     = $collection->getTable('easytranslate/project_cms_page');
                $projectTargetStoreTable = $collection->getTable('easytranslate/project_target_store');
                $collection->getSelect()->joinLeft(
                    ['etpcp' => $projectCmsPageTable],
                    'etpcp.page_id=main_table.page_id',
                    ['project_ids' => 'GROUP_CONCAT(DISTINCT etpcp.project_id)']
                );
                $collection->getSelect()->joinLeft(
                    ['etpts' => $projectTargetStoreTable],
                    'etpts.project_id=etpcp.project_id',
                    ['translated_stores' => 'GROUP_CONCAT(DISTINCT target_store_id)']
                );
                $collection->getSelect()->group('main_table.page_id');
            } else {
                $selectedCmsPageIds = $this->_getSelectedCmsPageIds();
                $collection->addFieldToFilter('main_table.page_id', ['in' => $selectedCmsPageIds]);
            }
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

    protected function _filterTranslatedCondition(
        Mage_Cms_Model_Resource_Page_Collection $collection,
        Mage_Adminhtml_Block_Widget_Grid_Column $column
    ): void {
        $value = $column->getFilter()->getValue();
        if ($value) {
            $collection->getSelect()->where('etpts.target_store_id=?', $value);
        }
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

    protected function _afterLoadCollection(): EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_AbstractEntity
    {
        $this->getCollection()->walk('afterLoad');
        $identifiers = $this->getCollection()->getColumnValues('identifier');
        Mage::getModel('easytranslate/content_generator_filter_cms')
            ->filterEntities($this->getCollection(), $identifiers);

        return parent::_afterLoadCollection();
    }
}
