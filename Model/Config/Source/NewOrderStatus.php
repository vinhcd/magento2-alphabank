<?php

namespace Monogo\Alphabank\Model\Config\Source;

class NewOrderStatus implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'pending_payment', 'label'=>__('Pending Payment')),
            array('value' => 'processing', 'label'=>__('Processing')),
        );
    }
}
