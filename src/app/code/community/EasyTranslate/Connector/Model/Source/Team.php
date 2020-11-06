<?php

declare(strict_types=1);

use EasyTranslate\Api\ApiException;
use EasyTranslate\Api\TeamApi;

class EasyTranslate_Connector_Model_Source_Team
{
    public function getOptions(): array
    {
        $apiConfiguration = Mage::getModel('easytranslate/config')->getApiConfiguration();
        $teamsApi         = new TeamApi($apiConfiguration);

        try {
            $userResponse = $teamsApi->getUser();
        } catch (ApiException $e) {
            return [];
        }

        return $userResponse->getTeams();
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
