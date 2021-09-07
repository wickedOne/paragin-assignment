<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Import\Processor;

use App\Entity\Remindo;
use App\Import\Enum\Entity;
use App\Import\Persistence\RemindoPersistence;
use App\Import\Processor\RemindoProcessor;
use PHPUnit\Framework\TestCase;

/**
 * Remindo Processor Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class RemindoProcessorTest extends TestCase
{
    /**
     * @throws \App\Doctrine\Exception\DoctrineTransactionException
     */
    public function testProcess(): void
    {
        $persistence = $this->getPersistence(1, 1);
        $processor = new RemindoProcessor($persistence);

        $processor->process([], new Remindo());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testSupports(): void
    {
        $persistence = $this->getPersistence(0, 0);
        $processor = new RemindoProcessor($persistence);

        self::assertTrue($processor->supports(Entity::TYPE_REMINDO));
        self::assertFalse($processor->supports('foo'));
    }

    /**
     * @param int $persistCount
     * @param int $flushCount
     *
     * @return \App\Import\Persistence\RemindoPersistence|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getPersistence(int $persistCount, int $flushCount)
    {
        $persistence = $this->getMockBuilder(RemindoPersistence::class)->disableOriginalConstructor()->getMock();

        $persistence
            ->expects($this->exactly($persistCount))
            ->method('persist')
        ;

        $persistence
            ->expects($this->exactly($flushCount))
            ->method('flush')
        ;

        return $persistence;
    }
}
