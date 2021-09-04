<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Processor;

use App\Entity\Remindo;
use App\Exception\ProcessorException;
use App\FileParser\FileParserFactory;
use App\Form\Data\UploadData;
use App\Processor\RemindoImportProcessor;
use App\Repository\RemindoRepository;
use App\Tests\Mock\TestFileParserFactory;
use App\Validation\RemindoImportValidator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Remindo ImportProcessor Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class RemindoImportProcessorTest extends TestCase
{
    private static $data = [
        ['ID', 'question 1'],
        ['Max question score:', 2],
        ['student 1', 1],
    ];

    /**
     * @group unit
     *
     * @throws \App\Exception\ProcessorException
     */
    public function testInvalidUpload(): void
    {
        $this->expectException(ProcessorException::class);

        $data = $this->getFormData(false);
        $factory = new FileParserFactory();
        $logger = $this->getLogger(1, 'error');
        $validator = new RemindoImportValidator();
        $em = $this->getEntityManager();

        $processor = new RemindoImportProcessor($factory, $logger, $validator, $em);
        $processor->process($data);
    }

    /**
     * @group unit
     *
     * @throws \App\Exception\ProcessorException
     */
    public function testUnknownMimeType(): void
    {
        $this->expectException(ProcessorException::class);

        $data = $this->getFormData(true, 2, 'video/mp4');
        $factory = new FileParserFactory();
        $logger = $this->getLogger(1, 'error');
        $validator = new RemindoImportValidator();
        $em = $this->getEntityManager();

        $processor = new RemindoImportProcessor($factory, $logger, $validator, $em);
        $processor->process($data);
    }

    /**
     * @group unit
     *
     * @throws \App\Exception\ProcessorException
     */
    public function testInvalidData(): void
    {
        $this->expectException(ProcessorException::class);

        $data = $this->getFormData(true, 1);
        $factory = new TestFileParserFactory(true, '', ['foo', 'bar']);
        $logger = $this->getLogger(0, 'error');
        $validator = new RemindoImportValidator();
        $em = $this->getEntityManager();

        $processor = new RemindoImportProcessor($factory, $logger, $validator, $em);
        $processor->process($data);
    }

    /**
     * @group unit
     */
    public function testProcess(): void
    {
        $data = $this->getFormData(true, 1);
        $factory = new TestFileParserFactory(true, '', self::$data);
        $logger = $this->getLogger(0, 'error');
        $validator = new RemindoImportValidator();

        $remindo = $this->remindo();
        $em = $this->getEntityManager($remindo);

        $processor = new RemindoImportProcessor($factory, $logger, $validator, $em);
        $test = $processor->process($data);

        self::assertSame($remindo, $test);
    }

    /**
     * @param int    $times
     * @param string $method
     *
     * @return mixed|\PHPUnit\Framework\MockObject\MockObject|\Psr\Log\LoggerInterface
     */
    private function getLogger(int $times, string $method)
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger->expects(self::exactly($times))->method($method);

        return $logger;
    }

    /**
     * @param \App\Entity\Remindo|null $remindo
     *
     * @return \Doctrine\ORM\EntityManagerInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getEntityManager(Remindo $remindo = null)
    {
        $remindo = $remindo ?: $this->remindo();
        $repo = $this->getMockBuilder(RemindoRepository::class)->disableOriginalConstructor()->getMock();
        $repo
            ->expects($this->any())
            ->method('getEager')
            ->willReturn($remindo)
        ;

        $em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $em->expects($this->any())
            ->method('getRepository')
            ->willReturn($repo)
        ;

        return $em;
    }

    /**
     * @return \App\Entity\Remindo
     */
    private function remindo(): Remindo
    {
        return (new Remindo())->setName('foo');
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
