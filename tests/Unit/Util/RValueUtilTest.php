<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Util;

use App\Util\RValueUtil;
use PHPUnit\Framework\TestCase;

/**
 * R-Value Util Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class RValueUtilTest extends TestCase
{
    /**
     * @dataProvider calculateDataProvider
     *
     * @param mixed $x
     * @param mixed $y
     * @param mixed $result
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testCalculate($x, $y, $result): void
    {
        self::assertSame($result, RValueUtil::calculate($x, $y));
    }

    /**
     * @return \Generator
     */
    public function calculateDataProvider(): \Generator
    {
        yield 'correl' => [
            'x' => [3, 2, 4, 5, 6],
            'y' => [9, 7, 12, 15, 17],
            'result' => 0.997,
        ];

        yield 'divide_by_zero' => [
            'x' => [],
            'y' => [],
            'result' => 0.0,
        ];
    }
}
