<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Util;

/**
 * P-Value Util.
 *
 * @author wicliff <wwolda@gmail.com>
 */
final class PValueUtil
{
    private const PRECISION = 1;

    /**
     * @param float $avg
     * @param int   $max
     *
     * @return float
     */
    public static function calculate(float $avg, int $max): float
    {
        if (0 === $max) {
            return 0.0;
        }

        return round($avg / $max, self::PRECISION);
    }
}
