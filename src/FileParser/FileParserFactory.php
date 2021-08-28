<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\FileParser;

use App\Exception\FileParseException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * File Parser Factory.
 *
 * @author wicliff <wwolda@gmail.com>
 */
final class FileParserFactory
{
    private const EXTENSION_XLSX = 'xlsx';
    private const EXTENSION_CSV = 'csv';

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return \App\FileParser\AbstractFileParser
     *
     * @throws \App\Exception\FileParseException
     */
    public function create(UploadedFile $file): AbstractFileParser
    {
        switch ($file->guessClientExtension()) {
            case self::EXTENSION_XLSX:
                return new XlsxFileParser($file);
            case self::EXTENSION_CSV:
                return new CsvFileParser($file);
            default:
                throw new FileParseException(sprintf('no parser found for extension %s', $file->guessClientExtension()));
        }
    }
}
