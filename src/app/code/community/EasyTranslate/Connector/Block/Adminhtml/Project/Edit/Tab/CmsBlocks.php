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
        $collection = Mage::getModel('cms/block')->getCollection()->addFieldToSelect('block_id')
            ->addFieldToSelect('identifier')
            ->addFieldToSelect('title')
            ->addFieldToSelect('is_active');

        if ($this->_getProject()) {
            $collection->addStoreFilter($this->_getProject()->getData('source_store_id'));
            if (!$this->_getProject()->canEditDetails()) {
                $selectedCmsBlockIds = $this->_getSelectedCmsBlockIds();
                if (!empty($selectedCmsBlockIds)) {
                    $collection->addFieldToFilter('main_table.block_id', ['in' => $selectedCmsBlockIds]);
                }
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

        return parent::_prepareColumns();
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
}
