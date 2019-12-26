<?php

namespace Monogo\Alphabank\Block;

use Magento\Framework\Phrase;
use Magento\Payment\Block\ConfigurableInfo;

class Info extends ConfigurableInfo
{
    /**
     * @param string $field
     * @return Phrase
     */
    protected function getLabel($field)
    {
        return parent::getLabel($field); //todo
    }

    /**
     * @param string $field
     * @param string $value
     * @return string | Phrase
     */
    protected function getValueView($field, $value)
    {
        return parent::getValueView($field, $value); //todo
    }
}
