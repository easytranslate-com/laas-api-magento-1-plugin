<?php

use EasyTranslate\Api\ApiException;
use EasyTranslate\Api\TeamApi;

class EasyTranslate_Connector_Model_Source_Team
{
    /**
     * @return mixed[]
     */
    public function getOptions()
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

    /**
     * @return mixed[]
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->getOptions() as $value => $label) {
            $options[] = ['value' => $value, 'label' => $label];
        }

        return $options;
    }
}
