<?php

declare(strict_types=1);

use EasyTranslate\Api\ApiException;
use EasyTranslate\Api\ProjectApi;

class EasyTranslate_Connector_Adminhtml_Easytranslate_ProjectController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction(): void
    {
        $this->loadLayout();
        $this->_setActiveMenu('system/easytranslate');
        $this->_title(Mage::helper('easytranslate')->__('EasyTranslate Projects'));
        $this->renderLayout();
    }

    protected function _initProject(): EasyTranslate_Connector_Model_Project
    {
        $this->_title($this->__('System'))
            ->_title($this->__('EasyTranslate Projects'));
        $projectId = $this->getRequest()->getParam('project_id', false);
        $project   = Mage::getModel('easytranslate/project');
        if ($projectId) {
            $project->load($projectId);
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
        if ($project->getId()) {
            $this->_title($project->getData('name'));
        } else {
            $this->_title($this->__('New Project'));
        }

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
            $this->_saveProjectPostData($project, $data);
            if (!$this->_validateStoreViews($data)) {
                $session->addWarning($this->_getHelper()
                    ->__('The source store view cannot also be a target store view.'));
            }
            $session->addSuccess($this->_getHelper()->__('The project has been saved.'));
        } catch (Mage_Core_Exception $e) {
            $session->addError($e->getMessage());
            $redirectBack = true;
        }

        if ($redirectBack) {
            $this->_redirect('*/*/edit', ['project_id' => $project->getId()]);

            return;
        }

        $this->_redirect('*/*/index');
    }

    protected function _saveProjectPostData(EasyTranslate_Connector_Model_Project $project, array $data): void
    {
        if (empty($data)) {
            Mage::throwException('Invalid POST data.');
        }
        $session = $this->_getSession();

        $project->addData($data);
        $session->setData('form_data', $data);

        if (isset($data['included_products']) && $project->canEditDetails()) {
            $products = $data['included_products'] ? explode(',', $data['included_products']) : [];
            $project->setData('posted_products', $products);
        }

        if (isset($data['included_categories']) && $project->canEditDetails()) {
            $categories = $data['included_categories'] ? explode(',', $data['included_categories']) : [];
            $project->setData('posted_categories', $categories);
        }
        if (isset($data['included_cmsBlocks']) && $project->canEditDetails()) {
            $cmsBlocks = $data['included_cmsBlocks'] ? explode(',', $data['included_cmsBlocks']) : [];
            $project->setData('posted_cmsBlocks', $cmsBlocks);
        }
        if (isset($data['included_cmsPages']) && $project->canEditDetails()) {
            $cmsPages = $data['included_cmsPages'] ? explode(',', $data['included_cmsPages']) : [];
            $project->setData('posted_cmsPages', $cmsPages);
        }

        $project->save();
        $session->setData('form_data', false);
        if (!$this->_validateStoreViews($data)) {
            $session->addWarning($this->_getHelper()
                ->__('The source store view cannot also be a target store view.'));
        }
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

    public function categoryGridAction(): void
    {
        $this->_initProject();
        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function cmsBlocksGridAction(): void
    {
        $this->_initProject();
        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function cmsPagesGridAction(): void
    {
        $this->_initProject();
        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function sendAction(): void
    {
        $magentoProject = $this->_initProject();
        $data           = $this->getRequest()->getPost();
        try {
            $this->_saveProjectPostData($magentoProject, $data);
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirectReferer();

            return;
        }
        $project       = Mage::getModel('easytranslate/bridge_project', $magentoProject);
        $configuration = Mage::getModel('easytranslate/config')->getApiConfiguration();
        $projectApi    = new ProjectApi($configuration);
        try {
            $projectResponse = $projectApi->sendProject($project);
            $magentoProject->setData('status', EasyTranslate_Connector_Model_Source_Status::SENT);
            $magentoProject->setData('external_id', $projectResponse->getId());
            $magentoProject->setData('price', $projectResponse->getPrice());
            $magentoProject->setData('currency', $projectResponse->getCurrency());
            $magentoProject->save();
            $message = $this->_getHelper()->__('The project has successfully been sent to EasyTranslate.');
            $this->_getSession()->addSuccess($message);
        } catch (ApiException $exception) {
            $this->_getSession()->addError($this->_getHelper()->__($exception->getMessage()));
        }
        $this->_redirectReferer();
    }

    public function acceptPriceAction(): void
    {
        $magentoProject = $this->_initProject();
        $project        = Mage::getModel('easytranslate/bridge_project', $magentoProject);
        $configuration  = Mage::getModel('easytranslate/config')->getApiConfiguration();
        $projectApi     = new ProjectApi($configuration);
        try {
            $projectApi->acceptPrice($project);
            $magentoProject->setData('status', EasyTranslate_Connector_Model_Source_Status::PRICE_ACCEPTED);
            $magentoProject->save();
            $message = $this->_getHelper()->__('The price of the project has successfully been accepted.');
            $this->_getSession()->addSuccess($message);
        } catch (ApiException $exception) {
            $this->_getSession()->addError($this->_getHelper()->__($exception->getMessage()));
        }
        $this->_redirectReferer();
    }

    public function declinePriceAction(): void
    {
        $magentoProject = $this->_initProject();
        $project        = Mage::getModel('easytranslate/bridge_project', $magentoProject);
        $configuration  = Mage::getModel('easytranslate/config')->getApiConfiguration();
        $projectApi     = new ProjectApi($configuration);
        try {
            $projectApi->declinePrice($project);
            $magentoProject->setData('status', EasyTranslate_Connector_Model_Source_Status::PRICE_DECLINED);
            $magentoProject->save();
            $message = $this->_getHelper()->__('The price of the project has successfully been declined.');
            $this->_getSession()->addSuccess($message);
        } catch (ApiException $exception) {
            $this->_getSession()->addError($this->_getHelper()->__($exception->getMessage()));
        }
        $this->_redirectReferer();
    }

    public function deleteAction(): void
    {
        $projectId = $this->getRequest()->getParam('project_id');
        if (!$projectId) {
            $this->_getSession()->addError($this->_getHelper()->__('Unable to find a project to delete.'));
            $this->_redirect('*/*/');

            return;
        }
        try {
            $project = Mage::getModel('easytranslate/project');
            $project->load($projectId);
            if (!$project->canEditDetails()) {
                $message = $this->_getHelper()
                    ->__('This project cannot be deleted, because it has already been sent to EasyTranslate.');
                Mage::throwException($message);
            }
            $project->delete();
            $this->_getSession()->addSuccess($this->_getHelper()->__('The project has been deleted.'));
            $this->_redirect('*/*/');
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/*/edit', ['project_id' => $projectId]);
        }
    }

    public function massDeleteAction(): void
    {
        $projectIds = $this->getRequest()->getParam('project_ids');

        if (!is_array($projectIds)) {
            $this->_getSession()->addError(Mage::helper('easytranslate')->__('Please select project(s).'));
            $this->_redirect('*/*/index');

            return;
        }

        try {
            $addWarning              = false;
            $numberOfDeletedProjects = 0;
            foreach ($projectIds as $projectId) {
                $project = Mage::getModel('easytranslate/project')->load($projectId);
                if ($project->canEditDetails()) {
                    $project->delete();
                    $numberOfDeletedProjects++;
                } else {
                    $addWarning = true;
                }
            }
            if ($addWarning) {
                $message = $this->_getHelper()
                    ->__('One or more projects could not be deleted, because they have already been sent to EasyTranslate.');
                $this->_getSession()->addWarning($message);
            }
            if ($numberOfDeletedProjects > 0) {
                $this->_getSession()->addSuccess(Mage::helper('adminhtml')
                    ->__('Total of %d record(s) have been deleted.', $numberOfDeletedProjects)
                );
            }
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()
                ->addException($e, Mage::helper('adminhtml')->__('An error occurred while deleting record(s).'));
        }

        $this->_redirect('*/*/index');
    }

    protected function _isAllowed(): bool
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/easytranslate');
    }
}
