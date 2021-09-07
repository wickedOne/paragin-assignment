<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\FileParser;

use App\Contract\FileParser\FileParserInterface;
use App\Contract\FileParser\Provider\DataProviderInterface;
use App\FileParser\Exception\FileParserException;
use App\FileParser\Provider\CsvDataProvider;
use App\Validation\FileValidator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Csv File Parser.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class CsvFileParser implements FileParserInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    private UploadedFile $file;

    /**
     * @var \Symfony\Component\Serializer\Serializer
     */
    private Serializer $serializer;

    /**
     * @var \App\Validation\FileValidator
     */
    private FileValidator $validator;

    /**
     * @var \App\Contract\FileParser\Provider\DataProviderInterface|\App\FileParser\Provider\CsvDataProvider
     */
    private DataProviderInterface $provider;

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile          $file
     * @param \App\Validation\FileValidator|null                           $validator
     * @param \Symfony\Component\Serializer\Serializer|null                $serializer
     * @param \App\Contract\FileParser\Provider\DataProviderInterface|null $provider
     */
    public function __construct(UploadedFile $file, FileValidator $validator = null, Serializer $serializer = null, DataProviderInterface $provider = null)
    {
        $this->file = $file;
        $this->validator = $validator ?: new FileValidator();
        $this->serializer = $serializer ?: new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $this->provider = $provider ?: new CsvDataProvider();
    }

    /**
     * {@inheritdoc}
     */
    public function parse(): array
    {
        if (false === $this->validate() || null === $csv = $this->getData()) {
            throw new FileParserException(sprintf('invalid file %s', $this->file->getFilename()));
        }

        try {
            return $this->serializer->decode($csv, 'csv', [CsvEncoder::NO_HEADERS_KEY => true]);
        } catch (UnexpectedValueException $e) {
            throw new FileParserException(sprintf('unable to decode file %s', $this->file->getFilename()), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validate(): bool
    {
        return $this->validator->validate($this->file);
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): ?string
    {
        return $this->provider->provide($this->file);
    }
}
