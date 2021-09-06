<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Entity;

use App\Entity\Question;
use App\Entity\Remindo;
use PHPUnit\Framework\TestCase;

/**
 * Question Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class QuestionTest extends TestCase
{
    /**
     * @dataProvider questionDataProvider
     *
     * @param mixed $sequence
     * @param mixed $max
     * @param mixed $remindo
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testFromImportData($sequence, $max, $remindo): void
    {
        $question = Question::fromImportData($sequence, $max, $remindo);

        self::assertSame($sequence, $question->getSequence());
        self::assertSame($max, $question->getMax());
        self::assertSame($remindo, $question->getRemindo());

        self::assertEmpty($question->getResults());
        self::assertSame(0.0, $question->getPValue());
        self::assertSame(0.0, $question->getRValue());
    }

    /**
     * @return \Generator
     */
    public function questionDataProvider(): \Generator
    {
        yield 'correct_data' => [
                'sequence' => 1,
                'max' => 2,
                'remindo' => new Remindo(),
            ];
    }
}
