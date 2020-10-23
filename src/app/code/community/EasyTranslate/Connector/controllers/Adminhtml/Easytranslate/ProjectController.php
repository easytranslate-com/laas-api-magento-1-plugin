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

    protected function _initProject()
    {
        $id      = $this->getRequest()->getParam('project_id', false);
        $project = Mage::getModel('easytranslate/project');
        if ($id) {
            $project->load($id);
            Mage::register('current_project', $project);
        }

        return $project;
    }

    public function newAction(): void
    {
        $this->_forward('edit');
    }

    public function editAction(): void
    {
        $project = $this->_initProject();

        if (!$project->getId() && $this->getRequest()->getParam('project_id')) {
            $this->_getSession()->addError($this->__('This project no longer exists.'));
            $this->_redirect('*/*/');

            return;
        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $project->setData($data);
        }

        $this->loadLayout();
        $this->_setActiveMenu('system/easytranslate');
        $this->renderLayout();
    }

    public function saveAction(): void
    {
        $redirectBack = $this->getRequest()->getParam('back', false);
        $data         = $this->getRequest()->getPost();
        if (!$data) {
            $this->_redirect('*/*/index');

            return;
        }
        $project = $this->_initProject();
        $session = $this->_getSession();

        if (!$project->getId() && $this->getRequest()->getParam('project_id')) {
            $session->addError($this->_getHelper()->__('This project no longer exists.'));
            $this->_redirect('*/*/index');

            return;
        }

        try {
            $project->addData($data);
            $session->setFormData($data);

            if (isset($data['included_products']) && $project->canEditDetails()) {
                $products = explode(',', $data['included_products']);
                $project->setData('posted_products', $products);
            }

            $project->save();
            $session->setFormData(false);
            if (!$this->_validateStoreViews($data)) {
                $session->addWarning($this->_getHelper()
                    ->__('The source store view cannot also be a target store view.'));
            }
            $session->addSuccess($this->_getHelper()->__('The project has been saved.'));
        } catch (Mage_Core_Exception $e) {
            $session->addError($e->getMessage());
            $redirectBack = true;
        } catch (Exception $e) {
            $session->addError($this->_getHelper()->__('An error occurred while saving the project.'));
            $redirectBack = true;
            Mage::logException($e);
        }

        if ($redirectBack) {
            $this->_redirect('*/*/edit', ['project_id' => $project->getId()]);

            return;
        }

        $this->_redirect('*/*/index');
    }

    protected function _validateStoreViews(array $data): bool
    {
        if (!isset($data['source_store_id'], $data['target_stores']) || !is_array($data['target_stores'])) {
            return true;
        }

        return !in_array($data['source_store_id'], $data['target_stores'], true);
    }

    public function productGridAction(): void
    {
        $this->_initProject();
        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function deleteAction(): void
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

    public function massDeleteAction(): void
    {
        $projectIds = $this->getRequest()->getParam('project_ids');

        if (!is_array($projectIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('easytranslate')
                ->__('Please select project(s).'));
            $this->_redirect('*/*/index');

            return;
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

        $this->_redirect('*/*/index');
    }

    protected function _isAllowed(): bool
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/easytranslate');
    }
}
