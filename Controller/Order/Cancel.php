<?php

namespace Monogo\Alphabank\Controller\Order;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Monogo\Alphabank\Model\Logger;
use Monogo\Alphabank\Model\RespondHandler;

class Cancel extends \Magento\Framework\App\Action\Action implements HttpPostActionInterface, CsrfAwareActionInterface
{
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var RespondHandler
     */
    private $respondHandler;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Cancel constructor.
     * @param Context $context
     * @param Session $checkoutSession
     * @param RespondHandler $respondHandler
     * @param Logger $logger
     */
    public function __construct(
        Context $context,
        Session $checkoutSession,
        RespondHandler $respondHandler,
        Logger $logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->respondHandler = $respondHandler;
        $this->logger = $logger;

        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $response = $this->getRequest()->getParams();
        $this->logger->debug($response);

        $this->respondHandler->setResponse($response);

        try {
            $this->respondHandler->handleFailure();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        if (isset($response['message'])) {
            $this->messageManager->addErrorMessage($response['message']);
        }
        $this->messageManager->addErrorMessage($this->respondHandler->getErrorMessage());
        $this->messageManager->addErrorMessage(__('Your order has been canceled'));

        $this->checkoutSession->restoreQuote();

        return $this->_redirect('checkout/cart');
    }

    /**
     * @inheritDoc
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}
