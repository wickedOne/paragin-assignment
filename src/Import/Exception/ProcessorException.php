<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Import\Exception;

use Throwable;

/**
 * Processor Exception.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class ProcessorException extends \RuntimeException
{
    /**
     * @param string          $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
