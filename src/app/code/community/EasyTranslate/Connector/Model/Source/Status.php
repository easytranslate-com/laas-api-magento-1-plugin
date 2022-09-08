<?php

class EasyTranslate_Connector_Model_Source_Status
{
    const OPEN = 'open';
    const SENT = 'sent';
    const PRICE_APPROVAL_REQUEST = 'price_approval_request';
    const PRICE_ACCEPTED = 'price_accepted';
    const PRICE_DECLINED = 'price_declined';
    const PARTIALLY_FINISHED = 'partially_finished';
    const FINISHED = 'finished';

    /**
     * @return mixed[]
     */
    public function getOptions()
    {
        /** @var EasyTranslate_Connector_Helper_Data $helper */
        $helper = Mage::helper('easytranslate');

        return [
            self::OPEN                   => $helper->__('Open'),
            self::SENT                   => $helper->__('Sent To EasyTranslate'),
            self::PRICE_APPROVAL_REQUEST => $helper->__('Price Approval Needed'),
            self::PRICE_ACCEPTED         => $helper->__('Price Accepted'),
            self::PRICE_DECLINED         => $helper->__('Price Declined'),
            self::PARTIALLY_FINISHED     => $helper->__('Partially Finished'),
            self::FINISHED               => $helper->__('Finished')
        ];
    }
}
