<?php

declare(strict_types=1);

class EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_CmsBlocks
    extends EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_AbstractEntity
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('cmsBlocks');
        $this->setDefaultSort('main_table.block_id');
        $this->setData('row_click_callback', $this->getJsObjectName() . '.easyTranslateRowClickCallback');
        $this->setData('checkbox_check_callback', $this->getJsObjectName() . '.easyTranslateCheckboxCheckCallback');
    }

    protected function _addColumnFilterToCollection($column
    ): EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_CmsBlocks {
        if (!$this->getCollection() || $column->getId() !== 'in_project') {
            return parent::_addColumnFilterToCollection($column);
        }

        $cmsBlockIds = $this->_getSelectedCmsBlockIds();
        $filterValue = (int)$column->getFilter()->getValue();
        if ($filterValue === 1) {
            // user filtered by in_project "yes"
            $this->getCollection()->addFieldToFilter('main_table.block_id', ['in' => $cmsBlockIds]);
        } elseif ($filterValue === 0 && !empty($cmsBlockIds)) {
            // user filtered by in_project "no"
            $this->getCollection()->addFieldToFilter('main_table.block_id', ['nin' => $cmsBlockIds]);
        }

        return $this;
    }

    protected function _prepareCollection(): EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_CmsBlocks
    {
        $this->setDefaultFilter(['in_project' => 1]);
        $collection = Mage::getResourceModel('easytranslate/cms_block_collection');

        if ($this->_getProject()) {
            $collection->addStoreFilter($this->_getProject()->getData('source_store_id'));
            if ($this->_getProject()->canEditDetails()) {
                // join stores in which blocks have already been added to a project / translated
                $projectCmsBlockTable    = $collection->getTable('easytranslate/project_cms_block');
                $projectTargetStoreTable = $collection->getTable('easytranslate/project_target_store');
                $collection->getSelect()->joinLeft(
                    ['etpcb' => $projectCmsBlockTable],
                    'etpcb.block_id=main_table.block_id',
                    ['project_ids' => 'GROUP_CONCAT(DISTINCT etpcb.project_id)']
                );
                $collection->getSelect()->joinLeft(
                    ['etpts' => $projectTargetStoreTable],
                    'etpts.project_id=etpcb.project_id',
                    ['translated_stores' => 'GROUP_CONCAT(DISTINCT target_store_id)']
                );
                $collection->getSelect()->group('main_table.block_id');
            } else {
                $selectedCmsBlockIds = $this->_getSelectedCmsBlockIds();
                $collection->addFieldToFilter('main_table.block_id', ['in' => $selectedCmsBlockIds]);
            }
        }

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns(): EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_CmsBlocks
    {
        if (!$this->_getProject() || $this->_getProject()->canEditDetails()) {
            $this->addColumn('in_project', [
                'header_css_class' => 'a-center',
                'inline_css'       => 'in-project',
                'type'             => 'checkbox',
                'name'             => 'in_project',
                'values'           => $this->_getSelectedCmsBlockIds(),
                'align'            => 'center',
                'index'            => 'block_id'
            ]);
        }
        $this->addColumn('block_id', [
            'header'       => Mage::helper('cms')->__('ID'),
            'sortable'     => true,
            'width'        => '60',
            'index'        => 'block_id',
            'filter_index' => 'main_table.block_id'
        ]);
        $this->addColumn('block_title', [
            'header' => Mage::helper('cms')->__('Title'),
            'index'  => 'title'
        ]);
        $this->addColumn('block_identifier', [
            'header' => Mage::helper('cms')->__('Identifier'),
            'index'  => 'identifier'
        ]);
        $this->addColumn('block_is_active', [
            'header'  => Mage::helper('cms')->__('Status'),
            'index'   => 'is_active',
            'type'    => 'options',
            'options' => [
                0 => Mage::helper('cms')->__('Disabled'),
                1 => Mage::helper('cms')->__('Enabled')
            ],
        ]);
        $this->addColumn('block_creation_time', [
            'header' => Mage::helper('cms')->__('Date Created'),
            'index'  => 'creation_time',
            'type'   => 'datetime',
        ]);

        $this->addColumn('block_update_time', [
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
        Mage_Cms_Model_Resource_Block_Collection $collection,
        Mage_Adminhtml_Block_Widget_Grid_Column $column
    ): void {
        $value = $column->getFilter()->getValue();
        if ($value) {
            $collection->getSelect()->where('etpts.target_store_id=?', $value);
        }
    }

    public function getGridUrl(): string
    {
        return $this->getUrl('*/*/cmsBlocksGrid', ['_current' => true]);
    }

    protected function _getSelectedCmsBlockIds(): array
    {
        $cmsBlocks = $this->getRequest()->getPost('included_cmsBlocks');
        if (is_null($cmsBlocks)) {
            if ($this->_getProject()) {
                return $this->_getProject()->getCmsBlocks();
            }

            return [];
        }

        return explode(',', $cmsBlocks);
    }

    public function getTabLabel(): string
    {
        return $this->_getHelper()->__('CMS Blocks');
    }

    public function getTabTitle(): string
    {
        return $this->_getHelper()->__('CMS Blocks');
    }

    protected function _afterLoadCollection(): EasyTranslate_Connector_Block_Adminhtml_Project_Edit_Tab_AbstractEntity
    {
        $this->getCollection()->walk('afterLoad');

        return parent::_afterLoadCollection();
    }
}
