<?php

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
    /**
     * @param \EasyTranslate\ProjectInterface $project
     * @return \EasyTranslate\Api\Response\CreateProjectResponse
     */
    public function sendProject($project)
    {
        $request = new CreateProjectRequest($project);

        $data = $this->sendRequest($request);

        return new CreateProjectResponse($data);
    }

    /**
     * @param \EasyTranslate\ProjectInterface $project
     * @return \EasyTranslate\Api\Response\AcceptPriceResponse
     */
    public function acceptPrice($project)
    {
        $request = new AcceptPriceRequest($project);

        $data = $this->sendRequest($request);

        return new AcceptPriceResponse($data);
    }

    /**
     * @param \EasyTranslate\ProjectInterface $project
     * @return \EasyTranslate\Api\Response\DeclinePriceResponse
     */
    public function declinePrice($project)
    {
        $request = new DeclinePriceRequest($project);

        $data = $this->sendRequest($request);

        return new DeclinePriceResponse($data);
    }
}
