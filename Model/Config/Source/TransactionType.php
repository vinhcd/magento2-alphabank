<?php

namespace Monogo\Alphabank\Model\Config\Source;

class TransactionType implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label'=>__('Payment (Sale)')),
            array('value' => 2, 'label'=>__('Authorization'))
        );
    }
}
