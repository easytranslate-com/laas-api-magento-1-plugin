<?php

declare(strict_types=1);

namespace EasyTranslate\Api;

use EasyTranslate\Api\Request\CreateProjectRequest;
use EasyTranslate\Api\Response\CreateProjectResponse;
use EasyTranslate\ProjectInterface;

class ProjectApi extends AbstractApi
{
    public function sendProject(ProjectInterface $project): CreateProjectResponse
    {
        $request = new CreateProjectRequest($project);

        $data = $this->sendRequest($request);

        return new CreateProjectResponse($data);
    }
}
