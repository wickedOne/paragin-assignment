<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Util;

/**
 * Ceasura Util.
 *
 * @author wicliff <wwolda@gmail.com>
 */
final class CeasuraUtil
{
    private const LOWER_BOUND = 20.0;
    private const LOWER_GRADE = 1;

    private const MIDDLE_BOUND = 70.0;
    private const MIDDLE_GRADE = 5.5;

    private const UPPER_BOUND = 100.0;
    private const UPPER_GRADE = 10;

    private const PRECISION = 1;

    /**
     * @param float $score
     * @param int   $max
     *
     * @return float
     */
    public static function calculate(float $score, int $max): float
    {
        $percentage = ($score / $max) * 100;

        if (self::LOWER_BOUND >= $percentage) {
            return self::LOWER_GRADE;
        }

        if (self::UPPER_BOUND === $percentage) {
            return self::UPPER_GRADE;
        }

        if (self::MIDDLE_BOUND === $percentage) {
            return self::MIDDLE_GRADE;
        }

        if (self::MIDDLE_BOUND > $percentage) {
            return round(self::LOWER_GRADE + ($percentage * (self::MIDDLE_GRADE - self::LOWER_GRADE) / self::MIDDLE_BOUND), self::PRECISION);
        }

        return round(self::MIDDLE_GRADE + ($percentage - self::MIDDLE_BOUND) * ((self::UPPER_GRADE - self::MIDDLE_GRADE) / (self::UPPER_BOUND - self::MIDDLE_BOUND)), self::PRECISION);
    }
}
