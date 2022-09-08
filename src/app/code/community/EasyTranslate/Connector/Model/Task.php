<?php

class EasyTranslate_Connector_Model_Task extends Mage_Core_Model_Abstract
{
    /**
     * @var EasyTranslate_Connector_Model_Project
     */
    protected $_project;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('easytranslate/task');
    }

    /**
     * @return \EasyTranslate_Connector_Model_Project
     */
    public function getProject()
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

    /**
     * @return \EasyTranslate_Connector_Model_Task
     */
    public function afterCommitCallback()
    {
        parent::afterCommitCallback();

        $this->getProject()->updateTasksStatus()->save();

        return $this;
    }
}
