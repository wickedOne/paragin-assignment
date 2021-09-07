<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Entity;

use App\Entity\Remindo;
use App\Entity\Respondent;
use PHPUnit\Framework\TestCase;

/**
 * Respondent Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class RespondentTest extends TestCase
{
    /**
     * @dataProvider respondentDataProvider
     *
     * @param mixed $name
     * @param mixed $remindo
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testFromImportData($name, $remindo): void
    {
        $respondent = Respondent::fromImportData($name, $remindo);

        self::assertSame($name, $respondent->getName());
        self::assertSame($remindo, $respondent->getRemindo());

        self::assertCount(0, $respondent->getResults());
        self::assertSame(1.0, $respondent->getCeasura());
    }

    /**
     * @return \Generator
     */
    public function respondentDataProvider(): \Generator
    {
        yield 'correct_data' => [
            'name' => 'test respondent',
            'remindo' => new Remindo(),
        ];
    }
}
