<?php

namespace Monogo\Alphabank\Model\Config\Source;

class Cctype extends \Magento\Payment\Model\Source\Cctype
{
    /**
     * {@inheritdoc}
     */
    public function getAllowedTypes()
    {
        return ['AE', 'VI', 'MC', 'JCB', 'DN'];
    }
}
