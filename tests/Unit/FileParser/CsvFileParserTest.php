<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\FileParser;

use App\Exception\FileParser\FileParserException;
use App\FileParser\CsvFileParser;
use App\FileParser\Provider\CsvDataProvider;
use App\Validation\FileValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Serializer;

/**
 * Csv FileParser Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class CsvFileParserTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param bool   $validatorResult
     * @param string $data
     * @param array  $result
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testParse(bool $validatorResult, string $data, array $result)
    {
        $file = $this->getUploadedFile();
        $validator = $this->mockValidator($file, $validatorResult);
        $provider = $this->mockProvider($file, $data);

        $parser = new CsvFileParser($file, $validator, null, $provider);

        self::assertSame($result, $parser->parse());
    }

    /**
     * @throws \App\Exception\FileParser\FileParserException
     */
    public function testInvalidFileException(): void
    {
        $this->expectException(FileParserException::class);

        $file = $this->getUploadedFile();
        $validator = $this->mockValidator($file, false);

        $parser = new CsvFileParser($file, $validator);

        $parser->parse();
    }

    /**
     * @throws \App\Exception\FileParser\FileParserException
     */
    public function testDecodeException(): void
    {
        $this->expectException(FileParserException::class);

        $file = $this->getUploadedFile();
        $validator = $this->mockValidator($file, true);
        $provider = $this->mockProvider($file, 'foo');

        $decoder = $this->getMockBuilder(CsvEncoder::class)->onlyMethods(['decode'])->getMock();
        $decoder->expects($this->once())
            ->method('decode')
            ->will($this->throwException(new UnexpectedValueException()))
        ;

        $serializer = new Serializer([], [$decoder]);

        $parser = new CsvFileParser($file, $validator, $serializer, $provider);

        $parser->parse();
    }

    /**
     * @return \Generator
     */
    public function dataProvider(): \Generator
    {
        yield 'valid_data' => [
            'validator_result' => true,
            'csv_data' => <<<'CSV'
foo,bar
baz,qux
CSV,
            'parse_result' => [
                ['foo', 'bar'],
                ['baz', 'qux'],
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
        $provider = $this->getMockBuilder(CsvDataProvider::class)->onlyMethods(['provide'])->getMock();
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
