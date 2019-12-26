<?php

namespace Monogo\Alphabank\Gateway\Command;

use Magento\Payment\Gateway\Command\GatewayCommand;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Sales\Model\Order\Payment;

class CaptureCommand extends GatewayCommand
{
    /**
     * @param array $commandSubject
     * @return void
     * @throws \Exception
     */
    public function execute(array $commandSubject)
    {
        $paymentDO = SubjectReader::readPayment($commandSubject);

        $payment = $paymentDO->getPayment();
        if (!$payment instanceof Payment) {
            return;
        }

        if (!$payment->getAuthorizationTransaction()) {
            return;
        }

        parent::execute($commandSubject);
    }
}
