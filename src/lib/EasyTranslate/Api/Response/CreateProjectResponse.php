<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Response;

use EasyTranslate\Api\ApiException;
use EasyTranslate\Project;
use EasyTranslate\ProjectInterface;
use EasyTranslate\Task;

class CreateProjectResponse extends AbstractResponse
{
    /**
     * @var ProjectInterface
     */
    private $project;

    public function mapFields(array $data): void
    {
        parent::mapFields($data);
        if (!isset($data['data']['type'], $data['data']['id']) || $data['data']['type'] !== 'project') {
            throw new ApiException(sprintf('Invalid response data in response class %s', self::class));
        }
        $project = new Project();
        $project->setId($data['data']['id']);
        $team = $this->extractTeam($data);
        $project->setTeam($team);
        $project->setSourceLanguage($data['data']['attributes']['source_language']);
        $project->setTargetLanguages($data['data']['attributes']['target_languages']);
        $project->setFolderId($data['data']['attributes']['folder_id']);
        $project->setFolderName($data['data']['attributes']['folder_name']);
        $project->setName($data['data']['attributes']['name']);
        $project->setWorkflow($data['data']['attributes']['workflow']);
        $tasks = $this->extractTasks($data, $project);
        $project->setTasks($tasks);
        if (isset($data['data']['attributes']['price'])) {
            $project->setPrice((float)$data['data']['attributes']['price']['amount']);
            $project->setCurrency($data['data']['attributes']['price']['currency']);
        }
        $this->project = $project;
    }

    private function extractTeam(array $data): string
    {
        if (!isset($data['included'])) {
            return '';
        }

        foreach ($data['included'] as $includedObject) {
            if (isset($includedObject['type']) && $includedObject['type'] === 'account') {
                return $includedObject['attributes']['team_identifier'];
            }
        }

        return '';
    }

    private function extractTasks(array $data, ProjectInterface $project): array
    {
        $tasks = [];
        if (!isset($data['included'])) {
            return $tasks;
        }

        foreach ($data['included'] as $includedObject) {
            if (!isset($includedObject['type']) || $includedObject['type'] !== 'task') {
                continue;
            }
            $task = new Task();
            $task->setId($includedObject['id']);
            $task->setProject($project);
            $task->setTargetLanguage($includedObject['attributes']['target_language']);
            $tasks[] = $task;
        }

        return $tasks;
    }

    public function getProject(): ProjectInterface
    {
        return $this->project;
    }
}
