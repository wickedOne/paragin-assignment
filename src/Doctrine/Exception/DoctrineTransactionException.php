<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Doctrine\Exception;

use Throwable;

/**
 * Doctrine Transaction Exception.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class DoctrineTransactionException extends \RuntimeException
{
    /**
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
