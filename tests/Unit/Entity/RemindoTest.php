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
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

/**
 * Remindo Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class RemindoTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testInstantiation(): void
    {
        $remindo = new Remindo();

        self::assertCount(0, $remindo->getResults());
        self::assertCount(0, $remindo->getRespondents());
        self::assertCount(0, $remindo->getQuestions());
        self::assertNull($remindo->getRespondentByName('foo'));
        self::assertNull($remindo->getQuestionBySequence(1));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testGetQuestionBySequence(): void
    {
        $remindo = new Remindo();
        $questionOne = Question::fromImportData(1, 2, $remindo);
        $questionTwo = Question::fromImportData(2, 2, $remindo);

        $refClass = new \ReflectionClass($remindo);
        $questions = $refClass->getProperty('questions');
        $questions->setAccessible(true);
        $questions->setValue($remindo, new ArrayCollection([$questionOne, $questionTwo]));

        self::assertSame($questionOne, $remindo->getQuestionBySequence(1));
        self::assertSame($questionTwo, $remindo->getQuestionBySequence(2));
        self::assertNull($remindo->getQuestionBySequence(3));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testGetRespondentByName(): void
    {
        $remindo = new Remindo();
        $respondentFoo = Respondent::fromImportData('foo', $remindo);
        $respondentBar = Respondent::fromImportData('bar', $remindo);

        $refClass = new \ReflectionClass($remindo);
        $respondents = $refClass->getProperty('respondents');
        $respondents->setAccessible(true);
        $respondents->setValue($remindo, new ArrayCollection([$respondentFoo, $respondentBar]));

        self::assertSame($respondentFoo, $remindo->getRespondentByName('foo'));
        self::assertSame($respondentBar, $remindo->getRespondentByName('bar'));
        self::assertNull($remindo->getRespondentByName('baz'));
    }
}
