<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\FileParser;

use App\Exception\FileParser\FileParseException;
use App\FileParser\Provider\XlsxDataProvider;
use App\FileParser\XlsxFileParser;
use App\Validation\FileValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Xlsx FileParser Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class XlsxFileParserTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @group unit
     *
     * @param bool   $validatorResult
     * @param string $data
     * @param array  $result
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testParse(bool $validatorResult, string $data, array $result): void
    {
        $file = $this->getUploadedFile();
        $validator = $this->mockValidator($file, $validatorResult);
        $provider = $this->mockProvider($file, $data);

        $parser = new XlsxFileParser($file, $validator, $provider);

        self::assertSame($result, $parser->parse());
    }

    /**
     * @throws \App\Exception\FileParser\FileParseException
     *
     * @group unit
     */
    public function testInvalidFileException(): void
    {
        $this->expectException(FileParseException::class);

        $file = $this->getUploadedFile();
        $validator = $this->mockValidator($file, false);

        $parser = new XlsxFileParser($file, $validator);

        $parser->parse();
    }

    /**
     * @throws \App\Exception\FileParser\FileParseException
     *
     * @group unit
     */
    public function testSimpleXlsxException(): void
    {
        $this->expectException(FileParseException::class);

        $file = $this->getUploadedFile();
        $validator = $this->mockValidator($file, true);
        $simpleXlsx = $this->getMockBuilder(\SimpleXLSX::class)->onlyMethods(['rows'])->getMock();
        $simpleXlsx
            ->expects($this->once())
            ->method('rows')
            ->willReturn(false)
        ;

        $parser = new XlsxFileParser($file, $validator, null, $simpleXlsx);

        $parser->parse();
    }

    /**
     * @return \Generator
     */
    public function dataProvider(): \Generator
    {
        $data = file_get_contents(__DIR__.'/../../Mock/books.xlsx');

        yield 'valid_data' => [
            'validator_result' => true,
            'xlsx_data' => $data,
            'parse_result' => [
                ['ISBN', 'title', 'author', 'publisher', 'ctry'],
                [618260307, 'The Hobbit', 'J. R. R. Tolkien', 'Houghton Mifflin', 'USA'],
                [908606664, 'Slinky Malinki', 'Lynley Dodd', 'Mallinson Rendel', 'NZ'],
            ],
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param bool                                                $result
     *
     * @return FileValidator
     */
    private function mockValidator(UploadedFile $file, bool $result)
    {
        $validator = $this->getMockBuilder(FileValidator::class)->onlyMethods(['validate'])->getMock();
        $validator
            ->expects($this->once())
            ->method('validate')
            ->with($file)
            ->willReturn($result)
        ;

        return $validator;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param string                                              $data
     *
     * @return \App\Contract\FileParser\Provider\DataProviderInterface
     */
    private function mockProvider(UploadedFile $file, string $data)
    {
        $provider = $this->getMockBuilder(XlsxDataProvider::class)->onlyMethods(['provide'])->getMock();
        $provider
            ->expects($this->once())
            ->method('provide')
            ->with($file)
            ->willReturn($data)
        ;

        return $provider;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    private function getUploadedFile(): UploadedFile
    {
        return new UploadedFile(__FILE__, 'foo');
    }
}
