<?php

declare(strict_types=1);

use EasyTranslate\Api\Callback\Event;

class EasyTranslate_Connector_CallbackController extends Mage_Core_Controller_Front_Action
{
    protected const VALID_CALLBACK_EVENTS
        = [
            Event::PRICE_APPROVAL_NEEDED,
            Event::TASK_COMPLETED
        ];

    /**
     * @var array
     */
    protected $_params;

    public function executeAction(): void
    {
        $request = $this->getRequest();
        if (!$this->_validateRequest($request)) {
            $this->_badRequestResponse('Could not validate request.');

            return;
        }

        try {
            switch ($this->_params['event']) {
                case Event::PRICE_APPROVAL_NEEDED:
                    Mage::getModel('easytranslate/callback_priceApprovalHandler')->handle($this->_params);
                    break;
                case Event::TASK_COMPLETED:
                    Mage::getModel('easytranslate/callback_taskCompletedHandler')->handle($this->_params);
                    break;
                default:
                    break;
            }
        } catch (Mage_Core_Exception $e) {
            $this->_badRequestResponse($e->getMessage());

            return;
        }

        $this->_successResponse();
    }

    protected function _validateRequest(Mage_Core_Controller_Request_Http $request): bool
    {
        if (!$request->isPost()) {
            return false;
        }

        if (stripos($this->_getCoreHttpHelper()->getHttpUserAgent(), 'EasyTranslate') === false) {
            return false;
        }

        $json = $request->getRawBody();
        try {
            $params               = Mage::helper('core')->jsonDecode($json);
            $secretParam          = EasyTranslate_Connector_Model_Callback_LinkGenerator::SECRET_PARAM;
            $params[$secretParam] = $request->getParam($secretParam);
            $this->_params        = $params;
        } catch (Zend_Json_Exception $e) {
            return false;
        }

        if (!$this->_validateParams()) {
            return false;
        }

        return true;
    }

    protected function _getCoreHttpHelper(): Mage_Core_Helper_Http
    {
        return Mage::helper('core/http');
    }

    protected function _validateParams(): bool
    {
        if (empty($this->_params)) {
            return false;
        }

        if (!isset($this->_params['event']) || !in_array($this->_params['event'], self::VALID_CALLBACK_EVENTS, true)) {
            return false;
        }

        if (!isset($this->_params['data'])) {
            return false;
        }

        if (!isset($this->_params[EasyTranslate_Connector_Model_Callback_LinkGenerator::SECRET_PARAM])) {
            return false;
        }

        return true;
    }

    protected function _badRequestResponse(string $message): void
    {
        $params = [
            'success' => false,
            'message' => 'Bad Request: ' . $message
        ];
        $this->_setJsonResponse($params, 400);
    }

    protected function _successResponse(): void
    {
        $params = [
            'success' => true,
        ];
        $this->_setJsonResponse($params);
    }

    protected function _setJsonResponse(array $params, $responseCode = 200): void
    {
        $this->getResponse()->setHeader('Content-type', 'application/json', true);
        $this->getResponse()->setHttpResponseCode($responseCode);
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($params));
    }
}
