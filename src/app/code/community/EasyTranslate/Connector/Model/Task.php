<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Task extends Mage_Core_Model_Abstract
{
    /**
     * @var EasyTranslate_Connector_Model_Project
     */
    protected $_project;

    protected function _construct(): void
    {
        $this->_init('easytranslate/task');
    }

    public function getProject(): EasyTranslate_Connector_Model_Project
    {
        if ($this->_project === null) {
            $this->_project = Mage::getModel('easytranslate/project');
            $projectId      = $this->getData('project_id');
            if ($projectId) {
                $this->_project->load($projectId);
            }
        }

        return $this->_project;
    }

    public function afterCommitCallback(): EasyTranslate_Connector_Model_Task
    {
        parent::afterCommitCallback();

        $this->getProject()->updateTasksStatus()->save();

        return $this;
    }
}
