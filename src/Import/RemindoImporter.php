<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Import;

use App\Contract\FileParser\FileParserFactoryInterface;
use App\Doctrine\Exception\DoctrineTransactionException;
use App\Entity\Remindo;
use App\Exception\FileParser\FileParserException;
use App\Exception\ProcessorException;
use App\Form\Data\UploadData;
use App\Validation\RemindoImportValidator;
use Psr\Log\LoggerInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

/**
 * Remindo Import Processor.
 *
 * @author wicliff <wwolda@gmail.com>
 */
final class RemindoImporter
{
    /**
     * @var \App\FileParser\FileParserFactory
     */
    private FileParserFactoryInterface $parserFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var \App\Validation\RemindoImportValidator
     */
    private RemindoImportValidator $validator;

    /**
     * @var \App\Import\RemindoDataProcessor
     */
    private RemindoDataProcessor $processor;

    /**
     * @param \App\FileParser\FileParserFactory      $parserFactory
     * @param \Psr\Log\LoggerInterface               $logger
     * @param \App\Validation\RemindoImportValidator $validator
     * @param \App\Import\RemindoDataProcessor       $processor
     */
    public function __construct(FileParserFactoryInterface $parserFactory, LoggerInterface $logger, RemindoImportValidator $validator, RemindoDataProcessor $processor)
    {
        $this->parserFactory = $parserFactory;
        $this->logger = $logger;
        $this->validator = $validator;
        $this->processor = $processor;
    }

    /**
     * @param \App\Form\Data\UploadData $data
     *
     * @return \App\Entity\Remindo
     *
     * @throws \App\Exception\ProcessorException
     */
    public function import(UploadData $data): Remindo
    {
        if (false === $data->isValidUpload()) {
            $this->logger->error('upload is not valid {file}', [
                'file' => $data->getName(),
            ]);

            throw new ProcessorException('invalid upload');
        }

        try {
            $parsed = $this->parserFactory->create($data->getFile())->parse();
        } catch (FileParserException $e) {
            $this->logger->error('unable to create parser for {file}', [
                'file' => $data->getFile()->getFilename(),
                'exception' => FlattenException::createFromThrowable($e),
            ]);

            throw new ProcessorException($e->getMessage(), $e);
        }

        if (false === $this->validator->validate($parsed)) {
            throw new ProcessorException('data does not validate');
        }

        try {
            return $this->processor
                ->withRemindo((new Remindo())->setName($data->getName()))
                ->withData($parsed)
                ->process()
            ;
        } catch (DoctrineTransactionException $e) {
            throw new ProcessorException('flushing error', $e);
        }
    }
}
