<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Util;

use App\Util\PValueUtil;
use PHPUnit\Framework\TestCase;

/**
 * P-Value Util Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class PValueUtilTest extends TestCase
{
    /**
     * @dataProvider calculateProvider
     *
     * @param float $average
     * @param int   $maximum
     * @param float $result
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testCalculate(float $average, int $maximum, float $result): void
    {
        self::assertSame($result, PValueUtil::calculate($average, $maximum));
    }

    /**
     * @return \Generator
     */
    public function calculateProvider(): \Generator
    {
        yield 'test_division' => [
            'average' => 1.0,
            'maximum' => 2,
            'result' => 0.5,
        ];

        yield 'test_division_by_zero' => [
            'average' => 0.0,
            'maximum' => 0,
            'result' => 0,
        ];

        yield 'test_incorrect_max' => [
            'average' => 1.3,
            'maximum' => 0,
            'result' => 0.0,
        ];

        yield 'test_precision' => [
            'average' => 1.0,
            'maximum' => 3,
            'result' => 0.3,
        ];
    }
}
