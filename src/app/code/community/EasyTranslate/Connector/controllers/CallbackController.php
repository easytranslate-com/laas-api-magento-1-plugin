<?php

use EasyTranslate\Api\Callback\Event;

class EasyTranslate_Connector_CallbackController extends Mage_Core_Controller_Front_Action
{
    const VALID_CALLBACK_EVENTS
        = [
            Event::PRICE_APPROVAL_NEEDED,
            Event::TASK_COMPLETED
        ];

    /**
     * @var array
     */
    protected $_params;

    /**
     * @return void
     */
    public function executeAction()
    {
        $request = $this->getRequest();
        if (!$this->_validateRequest($request)) {
            $this->_badRequestResponse('Could not validate request.');

            return;
        }

        try {
            switch ($this->_params['event']) {
                case Event::PRICE_APPROVAL_NEEDED:
                    Mage::getModel('easytranslate/callback_priceApprovalRequestHandler')->handle($this->_params);
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

    /**
     * @return bool
     */
    protected function _validateRequest(Mage_Core_Controller_Request_Http $request)
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

    /**
     * @return \Mage_Core_Helper_Http
     */
    protected function _getCoreHttpHelper()
    {
        return Mage::helper('core/http');
    }

    /**
     * @return bool
     */
    protected function _validateParams()
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

    /**
     * @return void
     * @param string $message
     */
    protected function _badRequestResponse($message)
    {
        $message = (string) $message;
        $params = [
            'success' => false,
            'message' => 'Bad Request: ' . $message
        ];
        $this->_setJsonResponse($params, 400);
    }

    /**
     * @return void
     */
    protected function _successResponse()
    {
        $params = [
            'success' => true,
        ];
        $this->_setJsonResponse($params);
    }

    /**
     * @return void
     */
    protected function _setJsonResponse(array $params, $responseCode = 200)
    {
        $this->getResponse()->setHeader('Content-type', 'application/json', true);
        $this->getResponse()->setHttpResponseCode($responseCode);
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($params));
    }
}
