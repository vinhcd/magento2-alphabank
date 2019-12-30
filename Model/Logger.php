<?php

namespace Monogo\Alphabank\Model;

use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

class Logger
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Logger constructor.
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(SerializerInterface $serializer, LoggerInterface $logger)
    {
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * @param array $data
     */
    public function debug($data)
    {
        try {
            $debugData = $this->serializer->serialize($data);
            $this->logger->debug($debugData);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
