<?php

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$projectTable = $installer->getConnection()
    ->newTable($installer->getTable('easytranslate/project'))
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

if ($installer->getConnection()->isTableExists($installer->getTable('easytranslate/project'))) {
    $installer->getConnection()->dropTable($installer->getTable('easytranslate/project'));
}

$installer->getConnection()->createTable($projectTable);

$projectTargetStoreTable = $installer->getConnection()
    ->newTable($installer->getTable('easytranslate/project_target_store'))
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
        $installer->getTable('easytranslate/project'),
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

if ($installer->getConnection()->isTableExists($installer->getTable('easytranslate/project_target_store'))) {
    $installer->getConnection()->dropTable($installer->getTable('easytranslate/project_target_store'));
}

$installer->getConnection()->createTable($projectTargetStoreTable);

$projectProductTable = $installer->getConnection()
    ->newTable($installer->getTable('easytranslate/project_product'))
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
        $installer->getTable('easytranslate/project'),
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

if ($installer->getConnection()->isTableExists($installer->getTable('easytranslate/project_product'))) {
    $installer->getConnection()->dropTable($installer->getTable('easytranslate/project_product'));
}

$installer->getConnection()->createTable($projectProductTable);

$projectCategoryTable = $installer->getConnection()
    ->newTable($installer->getTable('easytranslate/project_category'))
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
        $installer->getTable('easytranslate/project'),
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

if ($installer->getConnection()->isTableExists($installer->getTable('easytranslate/project_category'))) {
    $installer->getConnection()->dropTable($installer->getTable('easytranslate/project_category'));
}

$installer->getConnection()->createTable($projectCategoryTable);

$projectCmsBlockTable = $installer->getConnection()
    ->newTable($installer->getTable('easytranslate/project_cms_block'))
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
        $installer->getTable('easytranslate/project'),
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

if ($installer->getConnection()->isTableExists($installer->getTable('easytranslate/project_cms_block'))) {
    $installer->getConnection()->dropTable($installer->getTable('easytranslate/project_cms_block'));
}

$installer->getConnection()->createTable($projectCmsBlockTable);

$projectCmsPageTable = $installer->getConnection()
    ->newTable($installer->getTable('easytranslate/project_cms_page'))
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
        $installer->getTable('easytranslate/project'),
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

if ($installer->getConnection()->isTableExists($installer->getTable('easytranslate/project_cms_page'))) {
    $installer->getConnection()->dropTable($installer->getTable('easytranslate/project_cms_page'));
}

$installer->getConnection()->createTable($projectCmsPageTable);

$projectQueueTable = $installer->getConnection()
    ->newTable($installer->getTable('easytranslate/project_queue'))
    ->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'identity' => true,
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'Queue Item ID')
    ->addColumn('project_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'Project ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, [
        'primary'  => true,
        'nullable' => false,
        'unsigned' => true
    ], 'Store ID')
    ->addColumn('content_link', Varien_Db_Ddl_Table::TYPE_TEXT, 256, [
        'nullable' => false,
    ], 'Content Link')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, [], 'Created At')
    ->addColumn('processed_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, [], 'Processed At')
    ->addForeignKey(
        $installer->getFkName('easytranslate/project_queue', 'project_id', 'easytranslate/project',
            'project_id'),
        'project_id',
        $installer->getTable('easytranslate/project'),
        'project_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('easytranslate/project_queue', 'store_id', 'core/store', 'store_id'),
        'store_id',
        $installer->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('EasyTranslate Project Queue');

if ($installer->getConnection()->isTableExists($installer->getTable('easytranslate/project_queue'))) {
    $installer->getConnection()->dropTable($installer->getTable('easytranslate/project_queue'));
}

$installer->getConnection()->createTable($projectQueueTable);

$installer->endSetup();
