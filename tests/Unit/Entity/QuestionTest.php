<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Entity;

use App\Entity\Question;
use App\Entity\Remindo;
use App\Entity\Respondent;
use App\Entity\Result;
use App\Tests\Util;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @dataProvider questionDataProvider
     *
     * @param mixed $sequence
     * @param mixed $max
     * @param mixed $remindo
     */
    public function testPValue($sequence, $max, $remindo): void
    {
        $question = Question::fromImportData($sequence, $max, $remindo);
        $resultOne = Result::fromImportData(1.0, new Respondent(), $remindo, $question);
        $resultTwo = Result::fromImportData(2.0, new Respondent(), $remindo, $question);

        Util::setPrivateProperty($question, 'results', new ArrayCollection([$resultOne, $resultTwo]));

        self::assertSame(1.5, $question->getPValue());
    }

    /**
     * @dataProvider questionDataProvider
     *
     * @param mixed $sequence
     * @param mixed $max
     * @param mixed $remindo
     */
    public function testRValue($sequence, $max, $remindo): void
    {
        $respondent = Respondent::fromImportData('foo', $remindo);
        $question = Question::fromImportData($sequence, $max, $remindo);
        $resultOne = Result::fromImportData(1.0, $respondent, $remindo, $question);
        $resultTwo = Result::fromImportData(1.4, $respondent, $remindo, $question);

        Util::setPrivateProperty($respondent, 'results', new ArrayCollection([$resultOne, $resultTwo]));
        Util::setPrivateProperty($question, 'results', new ArrayCollection([$resultOne, $resultTwo]));

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
