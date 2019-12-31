<?php

namespace Monogo\Alphabank\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Payment\Gateway\ConfigInterface;

class AlphabankConfigProvider implements ConfigProviderInterface
{
    const CONFIG_SANDBOX = 'sandbox_mode';

    const CONFIG_API_URL = 'api_url';

    const CONFIG_API_URL_SANDBOX = 'api_url_sandbox';

    const CONFIG_CARDTYPES = 'cctypes';

    const CARD_TYPES = [
        'visa' => 'Visa',
        'mastercard' => 'Mastercard',
        'auto:MasterPass' => 'MasterPass',
        'maestro' => 'Maestro',
        'amex' => 'American Express',
        'diners' => 'Diners'
    ];

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function getConfig()
    {
        $config = [
            'payment' => [
                'alphabank' => [
                    'apiUrl' => $this->getApiUrl(),
                    'cardTypes' => $this->getCardTypes()
                ]
            ]
        ];
        return $config;
    }

    /**
     * @return string
     */
    protected function getApiUrl()
    {
        if ($this->config->getValue(self::CONFIG_SANDBOX) == \Monogo\Alphabank\Model\Config\Source\Mode::LIVE) {
            return $this->config->getValue(self::CONFIG_API_URL);
        }
        return $this->config->getValue(self::CONFIG_API_URL_SANDBOX);
    }

    /**
     * @return array
     */
    protected function getCardTypes()
    {
        $result = [];

        $selectedTypes = explode(',', $this->config->getValue(self::CONFIG_CARDTYPES));

        foreach ($selectedTypes as $selectedType) {
            if (isset(self::CARD_TYPES[$selectedType])) $result[$selectedType] = __(self::CARD_TYPES[$selectedType]);
        }

        return $result;
    }
}
