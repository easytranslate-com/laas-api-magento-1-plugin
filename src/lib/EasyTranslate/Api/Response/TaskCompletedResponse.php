<?php

namespace EasyTranslate\Api\Response;

use EasyTranslate\Api\ApiException;
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

    /**
     * @param mixed[] $data
     * @return void
     */
    public function mapFields($data)
    {
        parent::mapFields($data);
        if (!isset($data['data']['type'], $data['data']['id']) || $data['data']['type'] !== 'task') {
            throw new ApiException(sprintf('Invalid response data in response class %s', self::class));
        }
        $task = new Task();
        $task->setId($data['data']['id']);
        $this->projectId = $data['data']['attributes']['project_id'];
        $task->setTargetContent($data['data']['attributes']['target_content']);
        $task->setTargetLanguage($data['data']['attributes']['target_language']);
        $this->task = $task;
    }

    /**
     * @return \EasyTranslate\TaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @return string
     */
    public function getProjectId()
    {
        return $this->projectId;
    }
}
