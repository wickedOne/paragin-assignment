<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Validation;

use App\Validation\FileValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * FileValidator Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class FileValidatorTest extends TestCase
{
    /**
     * @dataProvider fileProvider
     *
     * @group unit
     *
     * @param string   $path
     * @param int|null $error
     * @param bool     $result
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testValidate(string $path, ?int $error, bool $result): void
    {
        $file = new UploadedFile($path, 'foo', null, $error, true);
        $validator = new FileValidator();

        self::assertSame($result, $validator->validate($file));
    }

    /**
     * @return \Generator
     */
    public function fileProvider(): \Generator
    {
        yield 'valid_file' => [
            'path' => __FILE__,
            'error' => null,
            'result' => true,
        ];

        yield 'invalid_file' => [
            'path' => __FILE__,
            'error' => \UPLOAD_ERR_INI_SIZE,
            'result' => false,
        ];
    }
}
