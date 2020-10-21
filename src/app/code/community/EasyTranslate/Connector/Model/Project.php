<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Project extends Mage_Core_Model_Abstract
{
    protected function _construct(): void
    {
        $this->_init('easytranslate/project');
    }
}
