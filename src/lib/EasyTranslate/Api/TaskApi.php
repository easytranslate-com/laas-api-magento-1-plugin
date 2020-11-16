<?php

declare(strict_types=1);

namespace EasyTranslate\Api;

use EasyTranslate\Api\Request\DownloadTaskTargetContentRequest;
use EasyTranslate\Api\Response\DownloadTaskTargetContentResponse;
use EasyTranslate\ProjectInterface;
use EasyTranslate\TaskInterface;

class TaskApi extends AbstractApi
{
    public function downloadTaskTarget(
        ProjectInterface $project,
        TaskInterface $task
    ): DownloadTaskTargetContentResponse {
        $request = new DownloadTaskTargetContentRequest($project, $task);

        $data = $this->sendRequest($request);

        return new DownloadTaskTargetContentResponse($data);
    }
}
