<?php

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

    /**
     * @return \EasyTranslate_Connector_Block_Adminhtml_Project_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('easytranslate/project')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return \EasyTranslate_Connector_Block_Adminhtml_Project_Grid
     */
    protected function _prepareColumns()
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
                'header' => $this->__('Project Name'),
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
                'header'   => $this->__('Price'),
                'index'    => 'price',
                'type'     => 'currency',
                'currency' => 'currency',
                'renderer' => 'easytranslate/adminhtml_widget_grid_column_renderer_currency',
                'default'  => $this->__('tbd'),
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

    /**
     * @return \EasyTranslate_Connector_Block_Adminhtml_Project_Grid
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');

        return parent::_afterLoadCollection();
    }

    /**
     * @return void
     */
    protected function _filterTargetStoreCondition($collection, $column)
    {
        $value = $column->getFilter()->getValue();
        if (!$value) {
            return;
        }

        $collection->addTargetStoreFilter((int)$value);
    }

    /**
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['project_id' => $row->getId()]);
    }

    /**
     * @return \EasyTranslate_Connector_Block_Adminhtml_Project_Grid
     */
    protected function _prepareMassaction()
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
