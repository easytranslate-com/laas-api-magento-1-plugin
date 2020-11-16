<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Content_Importer
{
    protected const IMPORTERS
        = [
            EasyTranslate_Connector_Model_Content_Generator_CmsBlock::ENTITY_CODE => 'easytranslate/content_importer_cmsBlock',
        ];

    public function import(array $data, array $storeIds): void
    {
        foreach (static::IMPORTERS as $code => $importer) {
            $importerData = array_filter($data, static function ($key) use ($code) {
                // if the key starts with the importer code, the importer can handle the data
                return strpos($key, $code) === 0;
            }, ARRAY_FILTER_USE_KEY);
            $this->_getImporterModel($importer)->import($importerData, $storeIds);
        }
    }

    protected function _getImporterModel(string $modelClass
    ): EasyTranslate_Connector_Model_Content_Importer_AbstractImporter {
        return Mage::getModel($modelClass);
    }
}
