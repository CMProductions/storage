<?php

namespace Cmp\Storage\Strategy;

use Cmp\Storage\AdapterInterface;
use Cmp\Storage\VirtualStorageInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class AbstractStorageCallStrategy.
 */
abstract class AbstractStorageCallStrategy implements VirtualStorageInterface, LoggerAwareInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    private $adapters;

    public function __construct()
    {
        $this->adapters = [];
    }

    public function addAdapter(AdapterInterface $adapter)
    {
        $this->log(
            LogLevel::INFO,
            'Add adapter "{{adapter}}" to strategy "{{strategy}}"',
            ['adapter' => $adapter->getName(), 'strategy' => $this->getStrategyName()]
        );
        $this->adapters[] = $adapter;
    }

    public function setAdapters(array $adapters)
    {
        $this->adapters = [];
        foreach ($adapters as $adapter) {
            $this->addAdapter($adapter);
        }
    }

    /**
     * @return VirtualStorageInterface[]
     */
    public function getAdapters()
    {
        return $this->adapters;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     */
    public function log($level, $message, array $context = array())
    {
        if (!$this->logger) {
            return;
        }
        $this->logger->log($level, $message, $context);
    }

    abstract public function getStrategyName();
}
