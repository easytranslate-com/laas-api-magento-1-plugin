<?php

namespace EasyTranslate\Api;

use EasyTranslate\Api\Request\RequestInterface;
use Exception;

abstract class AbstractApi
{
    const SANDBOX_URL = 'https://api.platform.sandbox.easytranslate.com/';

    const LIVE_URL = 'https://api.platform.easytranslate.com/';

    const DEFAULT_CURL_OPTIONS
        = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_FAILONERROR    => true,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json'
            ]
        ];

    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return \EasyTranslate\Api\Configuration
     */
    protected function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param \EasyTranslate\Api\Request\RequestInterface $request
     * @return mixed[]
     */
    protected function sendRequest($request)
    {
        $curl = curl_init();

        $this->setCurlUrl($curl, $request);
        $this->setCurlRequestType($curl, $request);
        $this->setCurlOptions($curl, $request);
        $this->setCurlPostData($request, $curl);

        $jsonResponse = curl_exec($curl);
        if (curl_errno($curl)) {
            $error = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error)) {
            throw new ApiException($error);
        }

        return json_decode($jsonResponse, true);
    }

    /**
     * @return void
     */
    private function setCurlUrl($curl, RequestInterface $request)
    {
        $url = self::SANDBOX_URL;
        if ($this->configuration->getEnvironment() === Environment::LIVE) {
            $url = self::LIVE_URL;
        }
        $url .= ltrim($request->getResource(), '/');
        curl_setopt($curl, CURLOPT_URL, $url);
    }

    /**
     * @return void
     */
    private function setCurlRequestType($curl, RequestInterface $request)
    {
        switch ($request->getType()) {
            case RequestInterface::TYPE_GET:
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                break;
            case RequestInterface::TYPE_POST:
                curl_setopt($curl, CURLOPT_POST, true);
                break;
            default:
                throw new Exception('Invalid request type.');
        }
    }

    /**
     * @return void
     */
    private function setCurlOptions($curl, RequestInterface $request)
    {
        $options = self::DEFAULT_CURL_OPTIONS;
        if ($request->requiresAuthentication()) {
            // TODO cache auth token
            $authApi                       = new AuthApi($this->configuration);
            $options[CURLOPT_HTTPHEADER][] = 'Authorization: Bearer ' . $authApi->obtainAccessToken()->getAccessToken();
        }
        if ($request->getType() === RequestInterface::TYPE_POST) {
            $options[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
        }
        foreach ($options as $option => $value) {
            curl_setopt($curl, $option, $value);
        }
    }

    /**
     * @param \EasyTranslate\Api\Request\RequestInterface $request
     * @return void
     */
    protected function setCurlPostData($request, $curl)
    {
        if ($request->getData()) {
            $data = json_encode(['data' => $request->getData()]);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
    }
}
