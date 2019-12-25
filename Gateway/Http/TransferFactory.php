<?php

namespace Monogo\Alphabank\Gateway\Http;

use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

class TransferFactory implements TransferFactoryInterface
{
    const CONFIG_SANDBOX = 'sandbox_mode';

    const CONFIG_API_URL = 'api_url';

    const CONFIG_API_URL_SANDBOX = 'api_url_sandbox';

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var TransferBuilder
     */
    private $transferBuilder;

    /**
     * @param ConfigInterface $config
     * @param TransferBuilder $transferBuilder
     */
    public function __construct(
        ConfigInterface $config,
        TransferBuilder $transferBuilder
    ) {
        $this->config = $config;
        $this->transferBuilder = $transferBuilder;
    }

    /**
     * Builds gateway transfer object
     *
     * @param array $request
     * @return TransferInterface
     */
    public function create(array $request)
    {
        return $this->transferBuilder
            ->setBody($request)
            ->setMethod('POST')
            ->setHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
            ->setUri($this->getApiUrl())
            ->build();
    }

    /**
     * @return string
     */
    private function getApiUrl()
    {
        if ($this->config->getValue(self::CONFIG_SANDBOX) == \Monogo\Alphabank\Model\Config\Source\Mode::LIVE) {
            return $this->config->getValue(self::CONFIG_API_URL);
        }
        return $this->config->getValue(self::CONFIG_API_URL_SANDBOX);
    }
}
