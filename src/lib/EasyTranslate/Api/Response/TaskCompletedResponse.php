<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Response;

class TaskCompletedResponse extends AbstractResponse
{
    /**
     * @var string
     */
    private $taskId = '';

    /**
     * @var string
     */
    private $projectId = '';

    /**
     * @var string
     */
    private $targetContent = '';

    /**
     * @var string
     */
    private $targetLanguage = '';

    public function mapFields(array $data): void
    {
        if (isset($data['data']['type'], $data['data']['id']) && $data['data']['type'] === 'task') {
            $this->taskId         = $data['data']['id'];
            $this->projectId      = $data['data']['attributes']['project_id'];
            $this->targetContent  = $data['data']['attributes']['target_content'];
            $this->targetLanguage = $data['data']['attributes']['target_language'];
        }
        parent::mapFields($data);
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }

    public function getProjectId(): string
    {
        return $this->projectId;
    }

    public function getTargetContent(): string
    {
        return $this->targetContent;
    }

    public function getTargetLanguage(): string
    {
        return $this->targetLanguage;
    }
}
