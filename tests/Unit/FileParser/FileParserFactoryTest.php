<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\FileParser;

use App\FileParser\CsvFileParser;
use App\FileParser\Exception\FileParserException;
use App\FileParser\FileParserFactory;
use App\FileParser\XlsxFileParser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * File Parser Factory Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class FileParserFactoryTest extends TestCase
{
    /**
     * @dataProvider fileParserDataProvider
     *
     * @param mixed $mime
     * @param mixed $result
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testCreate($mime, $result): void
    {
        $factory = new FileParserFactory();
        $file = new UploadedFile(__FILE__, 'foo', $mime);

        self::assertInstanceOf($result, $factory->create($file));
    }

    /**
     * @throws \App\FileParser\Exception\FileParserException
     */
    public function testUnknownMimeType(): void
    {
        $this->expectException(FileParserException::class);

        $factory = new FileParserFactory();
        $file = new UploadedFile(__FILE__, 'foo', 'video/mp4');

        $factory->create($file);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testExceptionMimeType(): void
    {
        $factory = new FileParserFactory();
        $file = new UploadedFile(__FILE__, 'foo', 'video/mp4');

        try {
            $factory->create($file);
        } catch (FileParserException $e) {
            self::assertStringContainsString('mp4', $e->getMessage());
        }
    }

    /**
     * @return \Generator
     */
    public function fileParserDataProvider(): \Generator
    {
        yield 'xlsx_file' => [
            'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'result' => XlsxFileParser::class,
        ];

        yield 'csv_file' => [
            'mime' => 'text/csv',
            'result' => CsvFileParser::class,
        ];
    }
}
