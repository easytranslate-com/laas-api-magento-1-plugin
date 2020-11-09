<?php

declare(strict_types=1);

namespace EasyTranslate\Api;

use EasyTranslate\Api\Request\AcceptPriceRequest;
use EasyTranslate\Api\Request\CreateProjectRequest;
use EasyTranslate\Api\Request\DeclinePriceRequest;
use EasyTranslate\Api\Response\AcceptPriceResponse;
use EasyTranslate\Api\Response\CreateProjectResponse;
use EasyTranslate\Api\Response\DeclinePriceResponse;
use EasyTranslate\ProjectInterface;

class ProjectApi extends AbstractApi
{
    public function sendProject(ProjectInterface $project): CreateProjectResponse
    {
        $request = new CreateProjectRequest($project);

        $data = $this->sendRequest($request);

        return new CreateProjectResponse($data);
    }

    public function acceptPrice(ProjectInterface $project): AcceptPriceResponse
    {
        $request = new AcceptPriceRequest($project);

        $data = $this->sendRequest($request);

        return new AcceptPriceResponse($data);
    }

    public function declinePrice(ProjectInterface $project): DeclinePriceResponse
    {
        $request = new DeclinePriceRequest($project);

        $data = $this->sendRequest($request);

        return new DeclinePriceResponse($data);
    }
}
