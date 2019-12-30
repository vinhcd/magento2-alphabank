<?php

namespace Monogo\Alphabank\Model;

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
    public function getTitle() {
        return __("Alphabank");
    }
}
