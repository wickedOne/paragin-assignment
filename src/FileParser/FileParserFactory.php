<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\FileParser;

use App\Contract\FileParser\FileParserFactoryInterface;
use App\Contract\FileParser\FileParserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * File Parser Factory.
 *
 * @author wicliff <wwolda@gmail.com>
 */
final class FileParserFactory implements FileParserFactoryInterface
{
    private const EXTENSION_XLSX = 'xlsx';
    private const EXTENSION_CSV = 'csv';

    /**
     * {@inheritdoc}
     */
    public function create(UploadedFile $file): FileParserInterface
    {
        switch ($file->guessClientExtension()) {
            case self::EXTENSION_XLSX:
                return new XlsxFileParser($file);
            case self::EXTENSION_CSV:
                return new CsvFileParser($file);
            default:
                throw new Exception\FileParserException(sprintf('no parser found for extension %s', $file->guessClientExtension() ?: '[unknown extension]'));
        }
    }
}
