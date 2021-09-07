<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Util;

use App\Util\CeasuraUtil;
use PHPUnit\Framework\TestCase;

/**
 * Ceasura Util Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class CeasuraUtilTest extends TestCase
{
    /**
     * @dataProvider ceasuraDataProvider
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @param mixed $score
     * @param mixed $result
     */
    public function testCeasuraCalculation($score, $result): void
    {
        self::assertSame($result, CeasuraUtil::calculate($score, 100));
    }

    /**
     * @return \Generator
     */
    public function ceasuraDataProvider(): \Generator
    {
        yield 'lower_bound' => [
            'score' => 16,
            'result' => 1.0,
        ];

        yield 'lower_bound_exact' => [
            'score' => 20,
            'result' => 1.0,
        ];

        yield 'middle_bound' => [
            'score' => 68,
            'result' => 5.4,
        ];

        yield 'middle_bound_exact' => [
            'score' => 70,
            'result' => 5.5,
        ];

        yield 'upper_bound' => [
            'score' => 100,
            'result' => 10.0,
        ];

        yield 'middle_upper_bound' => [
            'score' => 84,
            'result' => 7.6,
        ];

        yield 'lower_middle_bound' => [
            'score' => 63,
            'result' => 5.1,
        ];
    }
}
