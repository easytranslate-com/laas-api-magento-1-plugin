<?php

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$projectTableName = $installer->getTable('easytranslate/project');
$projectTable     = $installer->getConnection()
    ->newTable($projectTableName)
    ->addColumn('project_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'identity' => true,
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'Project ID')
    ->addColumn('external_id', Varien_Db_Ddl_Table::TYPE_TEXT, 255, [], 'External ID')
    ->addColumn('secret', Varien_Db_Ddl_Table::TYPE_TEXT, 255, [], 'Secret')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, [
        'nullable' => false
    ], 'Name')
    ->addColumn('team', Varien_Db_Ddl_Table::TYPE_TEXT, 255, [
        'nullable' => false
    ], 'Team')
    ->addColumn('source_store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, [
        'unsigned' => true
    ], 'Source Store ID')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 64, [
        'nullable' => false,
        'default'  => 'open'
    ], 'Status')
    ->addColumn('price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', [], 'Price')
    ->addColumn('currency', Varien_Db_Ddl_Table::TYPE_TEXT, 3, [], 'Currency')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, [], 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, [], 'Updated At')
    ->addIndex($installer->getIdxName('easytranslate/project', 'external_id'), ['external_id'],
        ['type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE])
    ->addForeignKey(
        $installer->getFkName('easytranslate/project', 'source_store_id', 'core/store', 'store_id'),
        'source_store_id',
        $installer->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('EasyTranslate Project');

if ($installer->getConnection()->isTableExists($projectTableName)) {
    $installer->getConnection()->dropTable($projectTableName);
}

$installer->getConnection()->createTable($projectTable);

$projectTargetStoreTableName = $installer->getTable('easytranslate/project_target_store');
$projectTargetStoreTable     = $installer->getConnection()
    ->newTable($projectTargetStoreTableName)
    ->addColumn('project_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'Project ID')
    ->addColumn('target_store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, [
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'Target Store ID')
    ->addIndex($installer->getIdxName('easytranslate/project_target_store', ['target_store_id']), ['target_store_id'])
    ->addForeignKey(
        $installer->getFkName('easytranslate/project_target_store', 'project_id', 'easytranslate/project',
            'project_id'),
        'project_id',
        $projectTableName,
        'project_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('easytranslate/project_target_store', 'target_store_id', 'core/store', 'store_id'),
        'target_store_id',
        $installer->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('EasyTranslate Project Target Store');

if ($installer->getConnection()->isTableExists($projectTargetStoreTableName)) {
    $installer->getConnection()->dropTable($projectTargetStoreTableName);
}

$installer->getConnection()->createTable($projectTargetStoreTable);

$projectProductTableName = $installer->getTable('easytranslate/project_product');
$projectProductTable     = $installer->getConnection()
    ->newTable($projectProductTableName)
    ->addColumn('project_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'Project ID')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'Product ID')
    ->addIndex($installer->getIdxName('easytranslate/project_product', ['product_id']), ['product_id'])
    ->addForeignKey(
        $installer->getFkName('easytranslate/project_product', 'project_id', 'easytranslate/project',
            'project_id'),
        'project_id',
        $projectTableName,
        'project_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('easytranslate/project_product', 'product_id', 'catalog/product', 'entity_id'),
        'product_id',
        $installer->getTable('catalog/product'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('EasyTranslate Project Product');

if ($installer->getConnection()->isTableExists($projectProductTableName)) {
    $installer->getConnection()->dropTable($projectProductTableName);
}

$installer->getConnection()->createTable($projectProductTable);

$projectCategoryTableName = $installer->getTable('easytranslate/project_category');
$projectCategoryTable     = $installer->getConnection()
    ->newTable($projectCategoryTableName)
    ->addColumn('project_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'Project ID')
    ->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'Category ID')
    ->addIndex($installer->getIdxName('easytranslate/project_category', ['category_id']), ['category_id'])
    ->addForeignKey(
        $installer->getFkName('easytranslate/project_category', 'project_id', 'easytranslate/project',
            'project_id'),
        'project_id',
        $projectTableName,
        'project_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('easytranslate/project_category', 'category_id', 'catalog/category', 'entity_id'),
        'category_id',
        $installer->getTable('catalog/category'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('EasyTranslate Project Category');

if ($installer->getConnection()->isTableExists($projectCategoryTableName)) {
    $installer->getConnection()->dropTable($projectCategoryTableName);
}

$installer->getConnection()->createTable($projectCategoryTable);

$projectCmsBlockTableName = $installer->getTable('easytranslate/project_cms_block');
$projectCmsBlockTable     = $installer->getConnection()
    ->newTable($projectCmsBlockTableName)
    ->addColumn('project_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'Project ID')
    ->addColumn('block_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'CMS Block ID')
    ->addIndex($installer->getIdxName('easytranslate/project_cms_block', ['block_id']), ['block_id'])
    ->addForeignKey(
        $installer->getFkName('easytranslate/project_cms_block', 'project_id', 'easytranslate/project',
            'project_id'),
        'project_id',
        $projectTableName,
        'project_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('easytranslate/project_cms_block', 'block_id', 'cms/block', 'block_id'),
        'block_id',
        $installer->getTable('cms/block'),
        'block_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('EasyTranslate Project CMS Block');

if ($installer->getConnection()->isTableExists($projectCmsBlockTableName)) {
    $installer->getConnection()->dropTable($projectCmsBlockTableName);
}

$installer->getConnection()->createTable($projectCmsBlockTable);

$projectCmsPageTableName = $installer->getTable('easytranslate/project_cms_page');
$projectCmsPageTable     = $installer->getConnection()
    ->newTable($projectCmsPageTableName)
    ->addColumn('project_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'Project ID')
    ->addColumn('page_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'CMS Page ID')
    ->addIndex($installer->getIdxName('easytranslate/project_cms_page', ['page_id']), ['page_id'])
    ->addForeignKey(
        $installer->getFkName('easytranslate/project_cms_page', 'project_id', 'easytranslate/project',
            'project_id'),
        'project_id',
        $projectTableName,
        'project_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('easytranslate/project_cms_page', 'page_id', 'cms/page', 'page_id'),
        'page_id',
        $installer->getTable('cms/page'),
        'page_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('EasyTranslate Project CMS Page');

if ($installer->getConnection()->isTableExists($projectCmsPageTableName)) {
    $installer->getConnection()->dropTable($projectCmsPageTableName);
}

$installer->getConnection()->createTable($projectCmsPageTable);

$taskTableName = $installer->getTable('easytranslate/task');
$taskTable     = $installer->getConnection()
    ->newTable($taskTableName)
    ->addColumn('task_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'identity' => true,
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'Task ID')
    ->addColumn('project_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'Project ID')
    // must not be unique, because we may import the same language to multiple stores, which results in multiple tasks
    ->addColumn('external_id', Varien_Db_Ddl_Table::TYPE_TEXT, 255, [], 'External ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, [
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'Store ID')
    ->addColumn('content_link', Varien_Db_Ddl_Table::TYPE_TEXT, 256, [], 'Content Link')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, [], 'Created At')
    ->addColumn('processed_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, [], 'Processed At')
    ->addForeignKey(
        $installer->getFkName('easytranslate/task', 'project_id', 'easytranslate/project',
            'project_id'),
        'project_id',
        $projectTableName,
        'project_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('easytranslate/task', 'store_id', 'core/store', 'store_id'),
        'store_id',
        $installer->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('EasyTranslate Task');

if ($installer->getConnection()->isTableExists($taskTableName)) {
    $installer->getConnection()->dropTable($taskTableName);
}

$installer->getConnection()->createTable($taskTable);

$installer->endSetup();
