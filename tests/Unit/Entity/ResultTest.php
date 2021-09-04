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
use PHPUnit\Framework\TestCase;

/**
 * Result Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class ResultTest extends TestCase
{
    /**
     * @dataProvider resultDataProvider
     *
     * @group unit
     *
     * @param mixed $score
     * @param mixed $respondent
     * @param mixed $remindo
     * @param mixed $question
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testFromImportData($score, $respondent, $remindo, $question): void
    {
        $result = Result::fromImportData($score, $respondent, $remindo, $question);

        self::assertSame($score, $result->getScore());
        self::assertSame($respondent, $result->getRespondent());
        self::assertSame($remindo, $result->getRemindo());
        self::assertSame($question, $result->getQuestion());
    }

    /**
     * @return \Generator
     */
    public function resultDataProvider(): \Generator
    {
        yield 'correct_data' => [
            'score' => 1.2,
            'respondent' => new Respondent(),
            'remindo' => new Remindo(),
            'question' => new Question(),
        ];
    }
}
