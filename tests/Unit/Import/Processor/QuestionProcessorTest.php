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
use App\Import\Processor\QuestionProcessor;
use PHPUnit\Framework\TestCase;

/**
 * Question Processor Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class QuestionProcessorTest extends TestCase
{
    /**
     * @dataProvider processProvider
     *
     * @param array $data
     * @param int   $persistCount
     * @param int   $flushCount
     *
     * @throws \App\Doctrine\Exception\DoctrineTransactionException
     */
    public function testProcess(array $data, int $persistCount, int $flushCount): void
    {
        $persistence = $this->getPersistence($persistCount, $flushCount);
        $processor = new QuestionProcessor($persistence);

        $processor->process($data, new Remindo());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testSupports(): void
    {
        $persistence = $this->getPersistence(0, 0);
        $processor = new QuestionProcessor($persistence);

        self::assertTrue($processor->supports(Entity::TYPE_QUESTION));
        self::assertFalse($processor->supports('foo'));
    }

    /**
     * @return \Generator
     */
    public function processProvider(): \Generator
    {
        yield 'no_questions' => [
            'data' => [
                ['header'],
            ],
            'persistence_persist' => 0,
            'persistence_flush' => 0,
        ];

        yield 'no_question_max_values' => [
            'data' => [
                ['header'],
                ['max_values'],
            ],
            'persistence_persist' => 0,
            'persistence_flush' => 0,
        ];

        yield 'question_max_values' => [
            'data' => [
                ['header'],
                ['max_values', '1', '2'],
            ],
            'persistence_persist' => 2,
            'persistence_flush' => 1,
        ];
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
