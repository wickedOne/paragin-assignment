<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Validation;

/**
 * Remindo Import Validator.
 *
 * @author wicliff <wwolda@gmail.com>
 */
final class RemindoImportValidator
{
    public const IDENTIFIER_QUESTIONS = 'ID';
    public const IDENTIFIER_MAX_SCORES = 'Max question score:';

    private const ROW_QUESTIONS = 0;
    private const ROW_MAX_SCORES = 1;
    private const ROW_RESPONDENTS = 2;

    private const COL_IDENTIFIER = 0;

    /**
     * @param array<int, array> $data
     *
     * @return bool
     */
    public function validate(array $data): bool
    {
        if (self::IDENTIFIER_QUESTIONS !== $data[self::ROW_QUESTIONS][self::COL_IDENTIFIER]
            || self::IDENTIFIER_MAX_SCORES !== $data[self::ROW_MAX_SCORES][self::COL_IDENTIFIER]
        ) {
            return false;
        }

        $count = \count($data);
        $questions = \count($data[self::ROW_MAX_SCORES]);

        for ($respondent = self::ROW_RESPONDENTS; $respondent < $count; ++$respondent) {
            if (\count($data[$respondent]) !== $questions) {
                return false;
            }
        }

        return true;
    }
}
