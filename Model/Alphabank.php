<?php

namespace Monogo\Alphabank\Model;

use Magento\Store\Model\ScopeInterface;

class Alphabank extends \Magento\Payment\Model\Method\AbstractMethod
{
    const CODE = 'alphabank';

    /**
     * @var string
     */
    protected $_code = self::CODE;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_scopeConfig
            ->getValue('payment/alphabank/title', ScopeInterface::SCOPE_STORE) ?: 'Alphabank';
    }
}
