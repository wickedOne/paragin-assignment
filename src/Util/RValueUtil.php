<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Util;

/**
 * R-Value Util.
 *
 * @author wicliff <wwolda@gmail.com>
 */
final class RValueUtil
{
    private const PRECISION = 3;

    /**
     * @param array<int, float> $scores
     * @param array<int, float> $grades
     *
     * @return float
     */
    public static function calculate(array $scores, array $grades): float
    {
        $size = \count($scores);

        $xy = [];
        $x2 = [];
        $y2 = [];

        for ($i = 0; $i < $size; ++$i) {
            $xy[] = $scores[$i] * $grades[$i];
            $x2[] = $scores[$i] ** 2;
            $y2[] = $grades[$i] ** 2;
        }

        $sub = ($size * array_sum($xy)) - (array_sum($scores) * array_sum($grades));
        $diff1 = ($size * array_sum($x2)) - (array_sum($scores) ** 2);
        $diff2 = ($size * array_sum($y2)) - (array_sum($grades) ** 2);

        if (0.0 === $sqrt = sqrt($diff1 * $diff2)) {
            return 0.0;
        }

        return round($sub / $sqrt, self::PRECISION);
    }
}
