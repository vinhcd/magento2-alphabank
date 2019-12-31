<?php

namespace Monogo\Alphabank\Controller\Payment;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Monogo\Alphabank\Model\AlphabankAdapter;
use Monogo\Alphabank\Model\Logger;

class Info extends \Magento\Framework\App\Action\Action
{
    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var AlphabankAdapter
     */
    protected $alphabankAdapter;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param Session $checkoutSession
     * @param AlphabankAdapter $alphabankAdapter
     * @param Logger $logger
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        Session $checkoutSession,
        AlphabankAdapter $alphabankAdapter,
        Logger $logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->jsonFactory = $jsonFactory;
        $this->alphabankAdapter = $alphabankAdapter;
        $this->logger = $logger;

        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $order = $this->checkoutSession->getLastRealOrder();

        $requestData = $this->alphabankAdapter->buildRequest($order);
        $this->logger->debug($requestData);
        $result = $this->jsonFactory->create();
        $result->setData($requestData);

        return $result;
    }
}
