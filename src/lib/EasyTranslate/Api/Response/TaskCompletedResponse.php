<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Response;

use EasyTranslate\Task;
use EasyTranslate\TaskInterface;

class TaskCompletedResponse extends AbstractResponse
{
    /**
     * @var TaskInterface
     */
    private $task;

    /**
     * @var string
     */
    private $projectId;

    public function mapFields(array $data): void
    {
        if (isset($data['data']['type'], $data['data']['id']) && $data['data']['type'] === 'task') {
            $task = new Task();
            $task->setId($data['data']['id']);
            $this->projectId = $data['data']['attributes']['project_id'];
            $task->setTargetContent($data['data']['attributes']['target_content']);
            $task->setTargetLanguage($data['data']['attributes']['target_language']);
            $this->task = $task;
        }
        parent::mapFields($data);
    }

    public function getTask(): TaskInterface
    {
        return $this->task;
    }

    public function getProjectId(): string
    {
        return $this->projectId;
    }
}
