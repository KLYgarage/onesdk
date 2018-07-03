<?php

namespace One;

use Psr\Log\LoggerInterface;

/**
 * dummy logger
 * @method void emergency($message, array $context)
 * @method void alert($message, array $context)
 * @method void critical($message, array $context)
 * @method void error($message, array $context)
 * @method void warning($message, array $context)
 * @method void notice($message, array $context)
 * @method void info($message, array $context)
 * @method void debug($message, array $context)
 * @method void log($level, $message, array $context)
 */
class DummyLogger implements LoggerInterface
{
    /**
     * {@inheritdoc}
     */
    public function emergency($message, array $context = array())
    {
    }

    /**
     * {@inheritdoc}
     */
    public function alert($message, array $context = array())
    {
    }

    /**
     * {@inheritdoc}
     */
    public function critical($message, array $context = array())
    {
    }
    /**
     * {@inheritdoc}
     */
    public function error($message, array $context = array())
    {
    }
    /**
     * {@inheritdoc}
     */
    public function warning($message, array $context = array())
    {
    }
    /**
     * {@inheritdoc}
     */
    public function notice($message, array $context = array())
    {
    }
    /**
     * {@inheritdoc}
     */
    public function info($message, array $context = array())
    {
        echo $message . "\n";
    }
    /**
     * {@inheritdoc}
     */
    public function debug($message, array $context = array())
    {
    }
    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = array())
    {
    }
}
