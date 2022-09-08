<?php

class EasyTranslate_Connector_Model_Content_Generator_Filter_Cms
{
    /**
     * Filters CMS entities (blocks and pages), so that they are unique: If there is a store-specific entity, remove the
     * global entity
     *
     * @throws Mage_Core_Exception
     */
    public function filterEntities(Mage_Core_Model_Resource_Db_Collection_Abstract $entities, array $identifiers)
    {
        if (!$entities instanceof Mage_Cms_Model_Resource_Block_Collection
            && !$entities instanceof Mage_Cms_Model_Resource_Page_Collection) {
            return $entities;
        }

        // make sure the stores are loaded
        $entities->walk('afterLoad');
        foreach ($identifiers as $identifier) {
            $entitiesWithIdentifier = $entities->getItemsByColumnValue('identifier', $identifier);
            if (count($entitiesWithIdentifier) < 2) {
                continue;
            }
            if (count($entitiesWithIdentifier) > 2) {
                Mage::throwException('The collection has more than two entities per identifier.');
            }
            list($entityWithIdentifier1, $entityWithIdentifier2) = $entitiesWithIdentifier;
            if (in_array(Mage_Core_Model_App::ADMIN_STORE_ID, $entityWithIdentifier1->getData('store_id'), false)) {
                $entityToRemove = $entityWithIdentifier1;
            } else {
                $entityToRemove = $entityWithIdentifier2;
            }
            $entities->removeItemByKey($entityToRemove->getId());
        }

        return $entities;
    }
}
