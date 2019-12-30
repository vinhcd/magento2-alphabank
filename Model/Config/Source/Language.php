<?php

namespace Monogo\Alphabank\Model\Config\Source;

class Language implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'en', 'label' => 'English'],
            ['value' => 'fr', 'label' => 'French'],
            ['value' => 'el', 'label' => 'Greek'],
        ];
    }
}
