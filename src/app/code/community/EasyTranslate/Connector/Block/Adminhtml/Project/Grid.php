<?php

declare(strict_types=1);

class EasyTranslate_Connector_Block_Adminhtml_Project_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('easytranslate_project_grid');
        $this->setDefaultSort('project_id');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection(): EasyTranslate_Connector_Block_Adminhtml_Project_Grid
    {
        $collection = Mage::getModel('easytranslate/project')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns(): EasyTranslate_Connector_Block_Adminhtml_Project_Grid
    {
        $this->addColumn('project_id',
            [
                'header' => $this->__('Project ID'),
                'index'  => 'project_id',
                'type'   => 'number',
                'width'  => '50px',
            ]
        );

        $this->addColumn('name',
            [
                'header' => $this->__('Name'),
                'index'  => 'name',
            ]
        );

        $this->addColumn('source_store_id',
            [
                'header'   => $this->__('Source Store View'),
                'index'    => 'source_store_id',
                'type'     => 'store',
                'sortable' => false,
            ]
        );

        $this->addColumn('target_stores',
            [
                'header'                    => $this->__('Target Store Views'),
                'index'                     => 'target_stores',
                'type'                      => 'store',
                'sortable'                  => false,
                'filter_condition_callback' => [$this, '_filterTargetStoreCondition'],
            ]
        );

        $this->addColumn('status',
            [
                'header'  => $this->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => Mage::getSingleton('easytranslate/source_status')->getOptions(),
            ]
        );

        $this->addColumn('price',
            [
                'header' => $this->__('Price'),
                'index'  => 'price',
                // we cannot use type price due to (potentially) different currencies!
                'type'   => 'number',
                // TODO add renderer, which shows currency symbol
            ]
        );

        $this->addColumn('created_at', [
            'header' => $this->__('Date Created'),
            'index'  => 'created_at',
            'type'   => 'datetime',
        ]);

        $this->addColumn('updated_at', [
            'header' => $this->__('Last Modified'),
            'index'  => 'updated_at',
            'type'   => 'datetime',
        ]);

        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection(): EasyTranslate_Connector_Block_Adminhtml_Project_Grid
    {
        $this->getCollection()->walk('afterLoad');

        return parent::_afterLoadCollection();
    }

    protected function _filterTargetStoreCondition($collection, $column): void
    {
        $value = $column->getFilter()->getValue();
        if (!$value) {
            return;
        }

        $collection->addTargetStoreFilter((int)$value);
    }

    public function getRowUrl($row): string
    {
        return $this->getUrl('*/*/edit', ['project_id' => $row->getId()]);
    }

    protected function _prepareMassaction(): EasyTranslate_Connector_Block_Adminhtml_Project_Grid
    {
        $modelPk = Mage::getModel('easytranslate/project')->getResource()->getIdFieldName();
        $this->setMassactionIdField($modelPk);
        $this->getMassactionBlock()->setData('form_field_name', 'project_ids');
        $this->getMassactionBlock()->addItem('delete', [
            'label' => $this->__('Delete'),
            'url'   => $this->getUrl('*/*/massDelete'),
        ]);

        return $this;
    }
}
