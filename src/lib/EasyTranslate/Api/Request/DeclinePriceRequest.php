<?php

namespace EasyTranslate\Api\Request;

use EasyTranslate\ProjectInterface;

class DeclinePriceRequest extends AbstractRequest
{
    /**
     * @var ProjectInterface
     */
    private $project;

    public function __construct(ProjectInterface $project)
    {
        $this->project = $project;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_POST;
    }

    /**
     * @return string
     */
    public function getResource()
    {
        return sprintf('api/v1/teams/%s/projects/%s/decline-price', $this->project->getTeam(), $this->project->getId());
    }
}
