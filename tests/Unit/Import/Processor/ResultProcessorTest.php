<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Import\Processor;

use App\Entity\Question;
use App\Entity\Remindo;
use App\Entity\Respondent;
use App\Import\Enum\Entity;
use App\Import\Persistence\RemindoPersistence;
use App\Import\Processor\ResultProcessor;
use App\Tests\Util;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

/**
 * Result Processor Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class ResultProcessorTest extends TestCase
{
    /**
     * @dataProvider processProvider
     *
     * @param array $data
     * @param array $respondents
     * @param array $questions
     * @param int   $batchSize
     * @param int   $persistCount
     * @param int   $flushCount
     *
     * @throws \App\Doctrine\Exception\DoctrineTransactionException
     */
    public function testProcess(array $data, array $respondents, array $questions, int $batchSize, int $persistCount, int $flushCount): void
    {
        $persistence = $this->getPersistence($persistCount, $flushCount);
        $processor = new ResultProcessor($persistence, $batchSize);
        $remindo = $this->remindo($respondents, $questions);

        $processor->process($data, $remindo);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testSupports(): void
    {
        $persistence = $this->getPersistence(0, 0);
        $processor = new ResultProcessor($persistence);

        self::assertTrue($processor->supports(Entity::TYPE_RESULT));
        self::assertFalse($processor->supports('foo'));
    }

    /**
     * @return \Generator
     */
    public function processProvider(): \Generator
    {
        yield 'no_respondents' => [
            'data' => [
                ['header'],
                ['header'],
            ],
            'respondents' => [],
            'questions' => [],
            'batch_size' => 50,
            'persistence_persist' => 0,
            'persistence_flush' => 1,
        ];

        yield 'no_questions' => [
            'data' => [
                ['header'],
                ['header'],
                ['foo'],
            ],
            'respondents' => ['foo'],
            'questions' => [],
            'batch_size' => 50,
            'persistence_persist' => 0,
            'persistence_flush' => 1,
        ];

        yield 'respondents_and_questions' => [
            'data' => [
                ['header'],
                ['header'],
                ['foo', 1 => '1', 2 => '2.2'],
            ],
            'respondents' => ['foo'],
            'questions' => [1 => 1, 2 => 3],
            'batch_size' => 50,
            'persistence_persist' => 2,
            'persistence_flush' => 1,
        ];

        yield 'multiple_respondents' => [
            'data' => [
                ['header'],
                ['header'],
                ['foo', 1 => '1.0', 2 => '2.2'],
                ['bar', 1 => '1.0', 2 => '2.2'],
            ],
            'respondents' => ['foo', 'bar'],
            'questions' => [1 => 1, 2 => 3],
            'batch_size' => 50,
            'persistence_persist' => 4,
            'persistence_flush' => 1,
        ];

        yield 'small_batch_size' => [
            'data' => [
                ['header'],
                ['header'],
                ['foo', 1 => '1.0', 2 => '2.2'],
            ],
            'respondents' => ['foo'],
            'questions' => [1 => 1, 2 => 3],
            'batch_size' => 1,
            'persistence_persist' => 2,
            'persistence_flush' => 2,
        ];

        yield 'non_matching_respondent' => [
            'data' => [
                ['header'],
                ['header'],
                ['bar', 1 => 1, 2 => '2.2'],
                ['foo', 1 => 1, 2 => '2.2'],
            ],
            'respondents' => ['foo'],
            'questions' => [1 => 1, 2 => 3],
            'batch_size' => 50,
            'persistence_persist' => 2,
            'persistence_flush' => 1,
        ];

        yield 'non_matching_question' => [
            'data' => [
                ['header'],
                ['header'],
                ['foo', 1 => 1, 3 => '2.2'],
                ['bar', 3 => '2.2', 1 => 1],
            ],
            'respondents' => ['foo', 'bar'],
            'questions' => [1 => 1, 2 => 3],
            'batch_size' => 50,
            'persistence_persist' => 2,
            'persistence_flush' => 1,
        ];
    }

    /**
     * @param array               $respondents
     * @param \App\Entity\Remindo $remindo
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    private function respondents(array $respondents, Remindo $remindo): ArrayCollection
    {
        $callback = static fn (string $v): Respondent => Respondent::fromImportData($v, $remindo);

        return new ArrayCollection(array_map($callback, $respondents));
    }

    /**
     * @param array               $questions
     * @param \App\Entity\Remindo $remindo
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    private function questions(array $questions, Remindo $remindo): ArrayCollection
    {
        $callback = static fn (int $k, int $v): Question => Question::fromImportData($k, $v, $remindo);

        return new ArrayCollection(array_map($callback, array_keys($questions), array_values($questions)));
    }

    /**
     * @param array $respondents
     * @param array $questions
     *
     * @return \App\Entity\Remindo
     */
    private function remindo(array $respondents, array $questions): Remindo
    {
        $remindo = (new Remindo())
            ->setName('foo');

        Util::setPrivateProperty($remindo, 'respondents', $this->respondents($respondents, $remindo));
        Util::setPrivateProperty($remindo, 'questions', $this->questions($questions, $remindo));

        return $remindo;
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
