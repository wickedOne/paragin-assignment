<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Exception\FileParser;

use App\Exception\FileParser\FileParserException;
use PHPUnit\Framework\TestCase;

/**
 * File Parser Exception Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class FileParserExceptionTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testException(): void
    {
        $exception = new FileParserException('foo', new FileParserException('bar'));

        self::assertSame('foo', $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertInstanceOf(FileParserException::class, $exception->getPrevious());
        self::assertSame('bar', $exception->getPrevious()->getMessage());
    }
}
