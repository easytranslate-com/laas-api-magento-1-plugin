<?php

declare(strict_types=1);

use EasyTranslate\Api\TeamApi;

class EasyTranslate_Connector_Model_Source_Team
{
    public function getOptions(): array
    {
        $apiConfiguration = Mage::getModel('easytranslate/config')->getApiConfiguration();
        $teamsApi         = new TeamApi($apiConfiguration);

        return $teamsApi->getUser()->getTeams();
    }

    public function toOptionArray(): array
    {
        $options = [];
        foreach ($this->getOptions() as $value => $label) {
            $options[] = ['value' => $value, 'label' => $label];
        }

        return $options;
    }
}
