<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Request;

use EasyTranslate\ProjectInterface;
use EasyTranslate\TaskInterface;

class DownloadTaskTargetContentRequest extends AbstractRequest
{
    /**
     * @var ProjectInterface
     */
    private $project;

    /**
     * @var TaskInterface
     */
    private $task;

    public function __construct(ProjectInterface $project, TaskInterface $task)
    {
        $this->project = $project;
        $this->task    = $task;
    }

    public function getResource(): string
    {
        return sprintf('api/v1/teams/%s/projects/%s/tasks/%s/download', $this->project->getTeam(),
            $this->project->getId(), $this->task->getId());
    }
}
