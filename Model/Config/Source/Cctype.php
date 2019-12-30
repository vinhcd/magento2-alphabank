<?php

namespace Monogo\Alphabank\Model\Config\Source;

class Cctype implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'visa', 'label' => __('Visa')],
            ['value' => 'mastercard', 'label' => __('Mastercard')],
            ['value' => 'auto:MasterPass', 'label' => __('MasterPass')],
            ['value' => 'maestro', 'label' => __('Maestro')],
            ['value' => 'amex', 'label' => __('American Express')],
            ['value' => 'diners', 'label' => __('Diners')]
        ];
    }
}
