<?php

$installer = $this;

$projectTableName = $installer->getTable('easytranslate/project');
$installer->getConnection()
    ->addColumn($projectTableName, 'automatic_import', [
        'type'    => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
        'comment' => 'Automatic Import',
    ]);

$installer->getConnection()->update($projectTableName, ['automatic_import' => 1]);
