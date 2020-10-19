<?php

declare(strict_types=1);

class EasyTranslate_Connector_Adminhtml_Easytranslate_ProjectController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction(): void
    {
        $this->loadLayout();
        $this->_setActiveMenu('system/easytranslate');
        $this->_title(Mage::helper('easytranslate')->__('EasyTranslate Projects'));
        $this->renderLayout();
    }

    public function newAction()
    {
        return $this->_forward('edit');
    }

    public function editAction()
    {
        $id      = $this->getRequest()->getParam('project_id');
        $project = Mage::getModel('easytranslate/project');

        if ($id) {
            $project->load($id);

            if (!$project->getId()) {
                $this->_getSession()->addError($this->__('This project no longer exists.'));

                return $this->_redirect('*/*/');
            }
        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $project->setData($data);
        }

        Mage::register('current_project', $project);

        $this->loadLayout();
        $this->_setActiveMenu('system/easytranslate');
        $this->renderLayout();

        return $this;
    }

    public function saveAction()
    {
        $redirectBack = $this->getRequest()->getParam('back', false);
        $data         = $this->getRequest()->getPost();
        if (!$data) {
            return $this->_redirect('*/*/index');
        }
        $id      = $this->getRequest()->getParam('project_id');
        $model   = Mage::getModel('easytranslate/project');
        $session = $this->_getSession();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $session->addError($this->_getHelper()->__('This project no longer exists.'));

                return $this->_redirect('*/*/index');
            }
        }

        try {
            $model->setData($data);
            $session->setFormData($data);
            $model->save();
            $session->setFormData(false);
            $session->addSuccess(
                $this->_getHelper()->__('The project has been saved.')
            );
        } catch (Mage_Core_Exception $e) {
            $session->addError($e->getMessage());
            $redirectBack = true;
        } catch (Exception $e) {
            $session->addError($this->_getHelper()->__('An error occurred while saving the project.'));
            $redirectBack = true;
            Mage::logException($e);
        }

        if ($redirectBack) {
            return $this->_redirect('*/*/edit', ['project_id' => $model->getId()]);
        }

        return $this->_redirect('*/*/index');
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('project_id');
        if (!$id) {
            Mage::getSingleton('adminhtml/session')->addError($this->_getHelper()
                ->__('Unable to find a project to delete.'));
            $this->_redirect('*/*/');

            return;
        }
        try {
            $project = Mage::getModel('easytranslate/project');
            $project->load($id);
            $project->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess($this->_getHelper()
                ->__('The project has been deleted.'));
            $this->_redirect('*/*/');
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/edit', ['block_id' => $id]);
        }
    }

    public function massDeleteAction()
    {
        $projectIds = $this->getRequest()->getParam('project_ids');

        if (!is_array($projectIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('easytranslate')
                ->__('Please select project(s).'));

            return $this->_redirect('*/*/index');
        }

        try {
            foreach ($projectIds as $projectId) {
                $model = Mage::getModel('easytranslate/project')->load($projectId);
                $model->delete();
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('adminhtml')->__('Total of %d record(s) have been deleted.', count($projectIds))
            );
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')
                ->addException($e, Mage::helper('adminhtml')->__('An error occurred while deleting record(s).'));
        }

        return $this->_redirect('*/*/index');
    }

    protected function _isAllowed(): bool
    {
        // TODO check path
        return Mage::getSingleton('admin/session')->isAllowed('system/easytranslate');
    }
}
