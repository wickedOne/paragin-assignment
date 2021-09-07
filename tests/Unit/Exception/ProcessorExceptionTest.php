<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Exception;

use App\Exception\ProcessorException;
use PHPUnit\Framework\TestCase;

/**
 * Processor Exception Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class ProcessorExceptionTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testException(): void
    {
        $exception = new ProcessorException('foo', new ProcessorException('bar'));

        self::assertSame('foo', $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertInstanceOf(ProcessorException::class, $exception->getPrevious());
        self::assertSame('bar', $exception->getPrevious()->getMessage());
    }
}
