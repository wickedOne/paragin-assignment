<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Doctrine\Exception;

use App\Doctrine\Exception\DoctrineTransactionException;
use PHPUnit\Framework\TestCase;

/**
 * Doctrine Transaction Exception Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class DoctrineTransactionExceptionTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testException(): void
    {
        $exception = new DoctrineTransactionException('foo', 422, new DoctrineTransactionException('bar'));

        self::assertSame('foo', $exception->getMessage());
        self::assertSame(422, $exception->getCode());
        self::assertInstanceOf(DoctrineTransactionException::class, $exception->getPrevious());
        self::assertSame('bar', $exception->getPrevious()->getMessage());
        self::assertSame(0, $exception->getPrevious()->getCode());
    }
}
