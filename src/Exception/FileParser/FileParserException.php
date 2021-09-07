<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Exception\FileParser;

use Throwable;

/**
 * File Parser Exception.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class FileParserException extends \RuntimeException
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
