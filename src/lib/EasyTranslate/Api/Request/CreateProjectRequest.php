<?php

namespace EasyTranslate\Api\Request;

use EasyTranslate\ProjectInterface;

class CreateProjectRequest extends AbstractRequest
{
    /**
     * @var ProjectInterface
     */
    private $project;

    public function __construct(ProjectInterface $project)
    {
        $this->project = $project;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_POST;
    }

    /**
     * @return string
     */
    public function getResource()
    {
        return sprintf('api/v1/teams/%s/projects', $this->project->getTeam());
    }

    /**
     * @return mixed[]
     */
    public function getData()
    {
        $data = [
            'type'       => 'project',
            'attributes' => [
                'name'             => $this->project->getName(),
                'source_language'  => $this->project->getSourceLanguage(),
                'target_languages' => $this->project->getTargetLanguages(),
                'content'          => $this->project->getContent(),
                'workflow'         => $this->project->getWorkflow(),
                'callback_url'     => $this->project->getCallbackUrl(),
            ],
        ];

        if ($this->project->getName() !== null) {
            $data['attributes']['name'] = $this->project->getName();
        }
        if ($this->project->getFolderName() !== null) {
            $data['attributes']['folder_name'] = $this->project->getFolderName();
        }
        if ($this->project->getFolderId() !== null) {
            $data['folder_id'] = $this->project->getFolderId();
        }

        return $data;
    }
}
