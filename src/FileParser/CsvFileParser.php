<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\FileParser;

use App\Exception\FileParseException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Csv File Parser.
 *
 * @author wicliff <wwolda@gmail.com>
 */
final class CsvFileParser extends AbstractFileParser
{
    /**
     * @var \Symfony\Component\Serializer\Serializer
     */
    private Serializer $serializer;

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function __construct(UploadedFile $file)
    {
        parent::__construct($file);

        $this->serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
    }

    /**
     * {@inheritDoc}
     */
    public function parse(): array
    {
        if (false === $this->validate() || false === $csv = file_get_contents($this->file->getPathname())) {
            throw new FileParseException(sprintf('invalid file %s', $this->file->getFilename()));
        }

        return $this->serializer->decode($csv, 'csv', [CsvEncoder::NO_HEADERS_KEY => true]);
    }
}
