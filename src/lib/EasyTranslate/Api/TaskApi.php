<?php

namespace EasyTranslate\Api;

use EasyTranslate\Api\Request\DownloadTaskTargetContentRequest;
use EasyTranslate\Api\Response\DownloadTaskTargetContentResponse;
use EasyTranslate\ProjectInterface;
use EasyTranslate\TaskInterface;

class TaskApi extends AbstractApi
{
    /**
     * @param \EasyTranslate\ProjectInterface $project
     * @param \EasyTranslate\TaskInterface $task
     * @return \EasyTranslate\Api\Response\DownloadTaskTargetContentResponse
     */
    public function downloadTaskTarget(
        $project,
        $task
    ) {
        $request = new DownloadTaskTargetContentRequest($project, $task);

        $data = $this->sendRequest($request);

        return new DownloadTaskTargetContentResponse($data);
    }
}
