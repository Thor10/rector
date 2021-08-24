<?php

/**
 * This file is part of the Tracy (https://tracy.nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace RectorPrefix20210824\Tracy\Bridges\Psr;

use RectorPrefix20210824\Psr;
use RectorPrefix20210824\Tracy;
/**
 * Tracy\ILogger to Psr\Log\LoggerInterface adapter.
 */
class TracyToPsrLoggerAdapter extends \RectorPrefix20210824\Psr\Log\AbstractLogger
{
    /** PSR-3 log level to Tracy logger level mapping */
    private const LEVEL_MAP = [\RectorPrefix20210824\Psr\Log\LogLevel::EMERGENCY => \RectorPrefix20210824\Tracy\ILogger::CRITICAL, \RectorPrefix20210824\Psr\Log\LogLevel::ALERT => \RectorPrefix20210824\Tracy\ILogger::CRITICAL, \RectorPrefix20210824\Psr\Log\LogLevel::CRITICAL => \RectorPrefix20210824\Tracy\ILogger::CRITICAL, \RectorPrefix20210824\Psr\Log\LogLevel::ERROR => \RectorPrefix20210824\Tracy\ILogger::ERROR, \RectorPrefix20210824\Psr\Log\LogLevel::WARNING => \RectorPrefix20210824\Tracy\ILogger::WARNING, \RectorPrefix20210824\Psr\Log\LogLevel::NOTICE => \RectorPrefix20210824\Tracy\ILogger::WARNING, \RectorPrefix20210824\Psr\Log\LogLevel::INFO => \RectorPrefix20210824\Tracy\ILogger::INFO, \RectorPrefix20210824\Psr\Log\LogLevel::DEBUG => \RectorPrefix20210824\Tracy\ILogger::DEBUG];
    /** @var Tracy\ILogger */
    private $tracyLogger;
    public function __construct(\RectorPrefix20210824\Tracy\ILogger $tracyLogger)
    {
        $this->tracyLogger = $tracyLogger;
    }
    /**
     * @param mixed[] $context
     */
    public function log($level, $message, $context = [])
    {
        $level = self::LEVEL_MAP[$level] ?? \RectorPrefix20210824\Tracy\ILogger::ERROR;
        if (isset($context['exception']) && $context['exception'] instanceof \Throwable) {
            $this->tracyLogger->log($context['exception'], $level);
            unset($context['exception']);
        }
        if ($context) {
            $message = ['message' => $message, 'context' => $context];
        }
        $this->tracyLogger->log($message, $level);
    }
}
