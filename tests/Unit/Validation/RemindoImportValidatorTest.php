<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Validation;

use App\Validation\RemindoImportValidator;
use PHPUnit\Framework\TestCase;

/**
 * Remindo Import Validator Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class RemindoImportValidatorTest extends TestCase
{
    /**
     * @dataProvider importValidatorDataProvider
     *
     * @param array $data
     * @param bool  $result
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testValidate($data, $result): void
    {
        $validator = new RemindoImportValidator();

        self::assertSame($result, $validator->validate($data));
    }

    /**
     * @return \Generator
     */
    public function importValidatorDataProvider(): \Generator
    {
        yield 'correct_headers' => [
            'data' => [
                [RemindoImportValidator::IDENTIFIER_QUESTIONS],
                [RemindoImportValidator::IDENTIFIER_MAX_SCORES],
            ],
            'result' => true,
        ];

        yield 'incorrect_headers' => [
            'data' => [
                [RemindoImportValidator::IDENTIFIER_MAX_SCORES],
                [RemindoImportValidator::IDENTIFIER_QUESTIONS],
            ],
            'result' => false,
        ];

        yield 'correct_number_of_questions' => [
            'data' => [
                [RemindoImportValidator::IDENTIFIER_QUESTIONS, 1, 2, 3],
                [RemindoImportValidator::IDENTIFIER_MAX_SCORES, 1, 2, 3],
                ['respondent', 1, 1, 0],
            ],
            'result' => true,
        ];

        yield 'incorrect_number_of_questions' => [
            'data' => [
                [RemindoImportValidator::IDENTIFIER_QUESTIONS, 1, 2, 3],
                [RemindoImportValidator::IDENTIFIER_MAX_SCORES, 1, 2, 3],
                ['respondent', 1, 1],
            ],
            'result' => false,
        ];
    }
}
