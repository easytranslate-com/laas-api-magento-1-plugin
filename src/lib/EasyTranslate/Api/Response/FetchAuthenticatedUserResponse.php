<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Response;

class FetchAuthenticatedUserResponse extends AbstractResponse
{
    /**
     * @var array
     */
    private $teams = [];

    public function mapFields(array $data): void
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

    public function getTeams(): array
    {
        return $this->teams;
    }
}
