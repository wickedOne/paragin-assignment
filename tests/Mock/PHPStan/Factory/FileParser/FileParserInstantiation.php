<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Mock\PHPStan\Factory\FileParser;

use App\Contract\FileParser\FileParserInterface;
use App\FileParser\CsvFileParser;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * FileParserInstantiation.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class FileParserInstantiation
{
    /**
     * @return \App\Contract\FileParser\FileParserInterface
     */
    public function getFileParser(): FileParserInterface
    {
        return new CsvFileParser(new UploadedFile(__FILE__, 'foo'));
    }

    /**
     * @return \App\Contract\FileParser\FileParserInterface
     */
    public function getDynamicFileParser(): FileParserInterface
    {
        $class = CsvFileParser::class;

        return new $class(new UploadedFile(__FILE__, 'foo'));
    }
}
