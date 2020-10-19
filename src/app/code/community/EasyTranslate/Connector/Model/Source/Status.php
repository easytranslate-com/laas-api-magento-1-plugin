<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Source_Status
{
    public const OPEN = 'open';
    public const SENT = 'sent';
    public const PRICE_APPROVAL = 'price_approval';
    public const PRICE_CONFIRMED = 'price_confirmed';
    public const PRICE_DECLINED = 'price_declined';
    public const PARTIALLY_FINISHED = 'partially_finished';
    public const FINISHED = 'finished';

    public function getOptions(): array
    {
        /** @var EasyTranslate_Connector_Helper_Data $helper */
        $helper = Mage::helper('easytranslate');

        return [
            self::OPEN               => $helper->__('Open'),
            self::SENT               => $helper->__('Sent To EasyTranslate'),
            self::PRICE_APPROVAL     => $helper->__('Price Approval Needed'),
            self::PRICE_CONFIRMED    => $helper->__('Price Confirmed'),
            self::PRICE_DECLINED     => $helper->__('Price Declined'),
            self::PARTIALLY_FINISHED => $helper->__('Partially Finished'),
            self::FINISHED           => $helper->__('Finished')
        ];
    }
}
