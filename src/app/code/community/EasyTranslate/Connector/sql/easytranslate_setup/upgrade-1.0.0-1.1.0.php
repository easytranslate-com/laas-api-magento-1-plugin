<?php

/** @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;

$installer->startSetup();

$projectTableName = $installer->getTable('easytranslate/project');
$installer->getConnection()
    ->addColumn($projectTableName, 'workflow', [
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'   => 64,
        'nullable' => false,
        'comment'  => 'Workflow',
    ]);

$installer->getConnection()->update($projectTableName, ['workflow' => 'translation']);

$installer->endSetup();
