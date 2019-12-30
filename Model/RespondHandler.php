<?php

namespace Monogo\Alphabank\Model;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Gateway\ConfigInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;

class RespondHandler extends DataObject
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var OrderSender
     */
    private $orderEmailSender;

    /**
     * @var string[]
     */
    private $response;

    /**
     * @var string
     */
    private $errorMessage;

    /**
     * RespondHandler constructor.
     * @param ConfigInterface $config
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderSender $orderEmailSender
     * @param array $data
     */
    public function __construct(
        ConfigInterface $config,
        OrderRepositoryInterface $orderRepository,
        OrderSender $orderEmailSender,
        array $data = []
    ) {
        $this->config = $config;
        $this->orderRepository = $orderRepository;
        $this->orderEmailSender = $orderEmailSender;

        parent::__construct($data);
    }

    /**
     * @param array $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function handleSuccess()
    {
        $this->validateResponse();

        $response = $this->response;

        /** @var Order $order */
        $order = $this->orderRepository->get($response[AlphabankAdapter::PARAM_ORDER_ID]);

        /** @var \Magento\Sales\Model\Order\Payment $payment */
        $payment = $order->getPayment();
        $payment->setLastTransId($response[AlphabankAdapter::PARAM_TXID])
            ->setTransactionId($response[AlphabankAdapter::PARAM_TXID])
            ->setAdditionalInformation(AlphabankAdapter::PARAM_REF, $this->getParamValue(AlphabankAdapter::PARAM_REF))
            ->setAdditionalInformation(AlphabankAdapter::PARAM_METHOD, $this->getParamValue(AlphabankAdapter::PARAM_METHOD))
            ->setAdditionalInformation(AlphabankAdapter::PARAM_RISK_SCORE, $this->getParamValue(AlphabankAdapter::PARAM_RISK_SCORE))
            ->setAdditionalInformation(AlphabankAdapter::PARAM_MESSAGE, $this->getParamValue(AlphabankAdapter::PARAM_MESSAGE))
            ->setIsTransactionClosed(1);

        $this->orderEmailSender->send($order);

        $status = $this->config->getValue(AlphabankAdapter::CONFIG_NEW_ORDER_STATUS);
        $status = !empty($status) ? $status : Order::STATE_PENDING_PAYMENT;
        $order->setStatus($status);
        $order->setState($status);
        $order->addStatusToHistory($order->getStatus(), __('Order processed successfully with transaction id %1', $this->getParamValue(AlphabankAdapter::PARAM_TXID)));
        $this->orderRepository->save($order);
    }

    /**
     * @throws LocalizedException
     */
    public function handleFailure()
    {
        $this->validateResponse();

        $this->handleErrorMessage();

        $response = $this->response;

        /** @var Order $order */
        $order = $this->orderRepository->get($response[AlphabankAdapter::PARAM_ORDER_ID]);
        $order->setStatus(Order::STATE_CANCELED);
        $order->setState(Order::STATE_CANCELED);
        $order->addStatusToHistory($order->getStatus(), __('Order processed failed with transaction id %1', $this->getParamValue(AlphabankAdapter::PARAM_TXID)));
        $this->orderRepository->save($order);
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    private function setErrorMessage(string $errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return void
     */
    private function handleErrorMessage()
    {
        switch ($this->response[AlphabankAdapter::PARAM_STATUS]) {
            case AlphabankAdapter::STATUS_CANCELED:
                $this->setErrorMessage(__('Transaction is aborted by user.'));
                break;
            case AlphabankAdapter::STATUS_REFUSED:
                $this->setErrorMessage(__('Transaction is refused by the bank.'));
                break;
            default:
                $this->setErrorMessage(__('There is an error with the bank transaction.'));
        }
    }

    /**
     * @throws LocalizedException
     */
    private function validateResponse()
    {
        if (!isset($this->response[AlphabankAdapter::PARAM_MID])
            || !isset($this->response[AlphabankAdapter::PARAM_ORDER_ID])
            || !isset($this->response[AlphabankAdapter::PARAM_STATUS])
            || !isset($this->response[AlphabankAdapter::PARAM_TXID])
        ) {
            throw new LocalizedException(__('Response invalid!'));
        }
    }

    /**
     * @param string $param
     * @return string
     */
    private function getParamValue($param)
    {
        if (isset($this->response[$param])) return $this->response[$param];

        return '';
    }
}
