<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Mock\PHPStan\Factory\FileParser;

use App\Contract\FileParser\FileParserFactoryInterface;
use App\Contract\FileParser\FileParserInterface;
use App\FileParser\CsvFileParser;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * FileParser Factory Instantiation.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class FileParserFactoryInstantiation implements FileParserFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(UploadedFile $file): FileParserInterface
    {
        return new CsvFileParser(new UploadedFile(__FILE__, 'foo'));
    }
}
