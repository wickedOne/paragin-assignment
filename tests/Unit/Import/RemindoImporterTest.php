<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Import;

use App\Entity\Remindo;
use App\FileParser\FileParserFactory;
use App\Form\Data\UploadData;
use App\Import\Exception\ProcessorException;
use App\Import\RemindoDataProcessor;
use App\Import\RemindoImporter;
use App\Tests\Mock\Import\FailingProcessor;
use App\Tests\Mock\Import\SupportingProcessor;
use App\Tests\Mock\Import\UnsupportingProcessor;
use App\Tests\Mock\TestFileParserFactory;
use App\Validation\RemindoImportValidator;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Remindo ImportProcessor Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class RemindoImporterTest extends TestCase
{
    private static $data = [
        ['ID', 'question 1'],
        ['Max question score:', 2],
        ['student 1', 1],
    ];

    /**
     * @throws \App\Import\Exception\ProcessorException
     */
    public function testInvalidUpload(): void
    {
        $this->expectException(ProcessorException::class);

        $data = $this->getFormData(false);
        $logger = $this->getLogger(1, 'upload is not valid {file}', ['file']);

        $factory = new FileParserFactory();
        $validator = new RemindoImportValidator();
        $processor = new RemindoDataProcessor([new UnsupportingProcessor()]);

        $importer = new RemindoImporter($factory, $logger, $validator, $processor);
        $importer->import($data);
    }

    /**
     * @throws \App\Import\Exception\ProcessorException
     */
    public function testUnknownMimeType(): void
    {
        $this->expectException(ProcessorException::class);

        $data = $this->getFormData(true, 2, 'video/mp4');
        $logger = $this->getLogger(1, 'unable to create parser for {file}', ['file', 'exception']);

        $factory = new FileParserFactory();
        $validator = new RemindoImportValidator();
        $processor = new RemindoDataProcessor([new UnsupportingProcessor()]);

        $importer = new RemindoImporter($factory, $logger, $validator, $processor);
        $importer->import($data);
    }

    /**
     * @throws \App\Import\Exception\ProcessorException
     */
    public function testInvalidData(): void
    {
        $this->expectException(ProcessorException::class);

        $data = $this->getFormData(true, 1);
        $logger = $this->getLogger(1, 'data does not validate');

        $factory = new TestFileParserFactory(true, '', ['foo', 'bar']);
        $validator = new RemindoImportValidator();
        $processor = new RemindoDataProcessor([new UnsupportingProcessor()]);

        $importer = new RemindoImporter($factory, $logger, $validator, $processor);
        $importer->import($data);
    }

    /**
     * test process.
     */
    public function testImportWithSupports(): void
    {
        $data = $this->getFormData(true, 1);
        $logger = $this->getLogger(0);

        $factory = new TestFileParserFactory(true, '', self::$data);
        $validator = new RemindoImportValidator();
        $processor = new RemindoDataProcessor([new SupportingProcessor(SupportingProcessor::class)]);

        $importer = new RemindoImporter($factory, $logger, $validator, $processor);
        $test = $importer->import($data);

        self::assertSame(SupportingProcessor::class, $test->getName());
    }

    /**
     * test process.
     */
    public function testImportWithoutSupports(): void
    {
        $data = $this->getFormData(true, 1);
        $logger = $this->getLogger(0);

        $factory = new TestFileParserFactory(true, '', self::$data);
        $validator = new RemindoImportValidator();
        $processor = new RemindoDataProcessor([new UnsupportingProcessor(UnsupportingProcessor::class)]);

        $importer = new RemindoImporter($factory, $logger, $validator, $processor);
        $test = $importer->import($data);

        self::assertNotSame(UnsupportingProcessor::class, $test->getName());
    }

    /**
     * test process multiple.
     */
    public function testImportMultipleProcessors(): void
    {
        $data = $this->getFormData(true, 1);
        $logger = $this->getLogger(0);

        $factory = new TestFileParserFactory(true, '', self::$data);
        $validator = new RemindoImportValidator();
        $processor = new RemindoDataProcessor([
            new UnsupportingProcessor('foo'),
            new SupportingProcessor('bar'),
        ]);

        $importer = new RemindoImporter($factory, $logger, $validator, $processor);
        $test = $importer->import($data);

        self::assertSame('bar', $test->getName());
    }

    /**
     * @throws \App\Import\Exception\ProcessorException
     */
    public function testTransactionException(): void
    {
        $this->expectException(ProcessorException::class);

        $data = $this->getFormData(true, 1);
        $logger = $this->getLogger(1, 'unable to persist data for {remindo}', ['remindo', 'exception']);

        $factory = new TestFileParserFactory(true, '', self::$data);
        $validator = new RemindoImportValidator();
        $processor = new RemindoDataProcessor([
            new FailingProcessor(),
        ]);

        $importer = new RemindoImporter($factory, $logger, $validator, $processor);
        $importer->import($data);
    }

    /**
     * @param int    $times
     * @param string $message
     * @param array  $parameters
     *
     * @return mixed|\PHPUnit\Framework\MockObject\MockObject|\Psr\Log\LoggerInterface
     */
    private function getLogger(int $times, string $message = '', array $parameters = [])
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger->expects(self::exactly($times))->method('error');

        if (0 !== $times) {
            $logger
                ->expects(self::exactly($times))
                ->method('error')
                ->with(
                    $message,
                    $this->callback(fn ($subject) => array_keys($subject) === $parameters)
                )
            ;
        }

        return $logger;
    }

    /**
     * @param bool   $valid
     * @param int    $fileCall
     * @param string $mime
     *
     * @return \App\Form\Data\UploadData|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getFormData(bool $valid, int $fileCall = 0, string $mime = 'text/csv')
    {
        $file = new UploadedFile(__FILE__, 'foo', $mime);

        $data = $this->getMockBuilder(UploadData::class)->getMock();
        $data->expects($this->once())
            ->method('isValidUpload')
            ->willReturn($valid)
        ;

        $data->expects($this->exactly($fileCall))
            ->method('getFile')
            ->willReturn($file)
        ;

        $data->expects($this->any())
            ->method('getName')
            ->willReturn('foo')
        ;

        return $data;
    }
}
