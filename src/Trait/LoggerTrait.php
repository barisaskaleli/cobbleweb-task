<?php

namespace App\Trait;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

trait LoggerTrait
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if (!$this->logger) {
            @trigger_error(
                LoggerInterface::class . ' dependency not found.'
                . ' Using ' . NullLogger::class
            );

            return new NullLogger();
        }

        return $this->logger;
    }

    /**
     * @required
     * @param LoggerInterface $logger
     *
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }
}
