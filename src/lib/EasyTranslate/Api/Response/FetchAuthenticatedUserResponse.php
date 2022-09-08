<?php

namespace EasyTranslate\Api\Response;

class FetchAuthenticatedUserResponse extends AbstractResponse
{
    /**
     * @var array
     */
    private $teams = [];

    /**
     * @param mixed[] $data
     * @return void
     */
    public function mapFields($data)
    {
        foreach ((array)$data['included'] as $includedAccount) {
            if ($includedAccount['type'] === 'account'
                && isset($includedAccount['attributes']['company_name'], $includedAccount['attributes']['team_identifier'])) {
                $this->teams[$includedAccount['attributes']['team_identifier']]
                    = $includedAccount['attributes']['company_name'];
            }
        }
        parent::mapFields($data);
    }

    /**
     * @return mixed[]
     */
    public function getTeams()
    {
        return $this->teams;
    }
}
