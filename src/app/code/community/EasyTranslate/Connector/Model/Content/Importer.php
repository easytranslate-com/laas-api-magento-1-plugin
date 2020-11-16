<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Content_Importer
{
    protected const IMPORTERS
        = [
            EasyTranslate_Connector_Model_Content_Generator_CmsBlock::ENTITY_CODE => 'easytranslate/content_importer_cmsBlock',
            EasyTranslate_Connector_Model_Content_Generator_CmsPage::ENTITY_CODE  => 'easytranslate/content_importer_cmsPage',
        ];

    public function import(array $data, int $sourceStoreId, array $targetStoreIds): void
    {
        foreach (static::IMPORTERS as $code => $importer) {
            $importerData = array_filter($data, static function ($key) use ($code) {
                // if the key starts with the importer code, the importer can handle the data
                return strpos($key, $code) === 0;
            }, ARRAY_FILTER_USE_KEY);
            $this->_getImporterModel($importer)->import($importerData, $sourceStoreId, $targetStoreIds);
        }
    }

    protected function _getImporterModel(string $modelClass
    ): EasyTranslate_Connector_Model_Content_Importer_AbstractImporter {
        return Mage::getModel($modelClass);
    }
}
