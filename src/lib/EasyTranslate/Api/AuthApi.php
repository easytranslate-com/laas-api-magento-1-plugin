<?php

namespace EasyTranslate\Api;

use EasyTranslate\Api\Request\ObtainAccessTokenRequest;
use EasyTranslate\Api\Request\RequestInterface;
use EasyTranslate\Api\Response\ObtainAccessTokenResponse;

class AuthApi extends AbstractApi
{
    /**
     * @return \EasyTranslate\Api\Response\ObtainAccessTokenResponse
     */
    public function obtainAccessToken()
    {
        $request = new ObtainAccessTokenRequest($this->getConfiguration());
        $data    = $this->sendRequest($request);

        return new ObtainAccessTokenResponse($data);
    }

    /**
     * @param \EasyTranslate\Api\Request\RequestInterface $request
     * @return void
     */
    protected function setCurlPostData($request, $curl)
    {
        if ($request->getData()) {
            $data = json_encode($request->getData());
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
    }
}
