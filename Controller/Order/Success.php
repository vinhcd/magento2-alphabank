<?php

namespace Monogo\Alphabank\Controller\Order;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Monogo\Alphabank\Model\AlphabankAdapter;
use Monogo\Alphabank\Model\Logger;
use Monogo\Alphabank\Model\RespondHandler;

class Success extends \Magento\Framework\App\Action\Action implements HttpPostActionInterface, CsrfAwareActionInterface
{
    /**
     * @var RespondHandler
     */
    private $respondHandler;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Success constructor.
     * @param Context $context
     * @param RespondHandler $respondHandler
     * @param Logger $logger
     */
    public function __construct(
        Context $context,
        RespondHandler $respondHandler,
        Logger $logger
    ) {
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

        if (isset($response[AlphabankAdapter::PARAM_STATUS]) && $response[AlphabankAdapter::PARAM_STATUS] == AlphabankAdapter::STATUS_CAPTURED) {
            try {
                $this->respondHandler->handleSuccess();
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        } else {
            try {
                $this->respondHandler->handleFailure();
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
            $this->messageManager->addErrorMessage(__('This payment method is currently set to Authorize only.'));
        }

        return $this->_redirect('checkout/onepage/success');
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
