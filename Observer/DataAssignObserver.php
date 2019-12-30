<?php

namespace Monogo\Alphabank\Observer;

use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;

class DataAssignObserver extends AbstractDataAssignObserver
{
    /**
     * @var array
     */
    protected $additionalInformationList = [
        'payMethod'
    ];

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $data = $this->readDataArgument($observer);

        $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);
        if (!is_array($additionalData)) {
            return;
        }

        $paymentInfo = $this->readPaymentModelArgument($observer);

        foreach ($this->additionalInformationList as $additionalInformationKey) {
            $value = isset($additionalData[$additionalInformationKey])
                ? $additionalData[$additionalInformationKey]
                : null;

            if ($value === null) {
                continue;
            }

            $paymentInfo->setAdditionalInformation(
                $additionalInformationKey,
                $value
            );

            $paymentInfo->setData(
                $additionalInformationKey,
                $value
            );
        }
    }
}
