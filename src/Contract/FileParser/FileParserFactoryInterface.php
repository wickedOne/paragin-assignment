<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Contract\FileParser;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * FileParser Factory Interface.
 *
 * @author wicliff <wwolda@gmail.com>
 */
interface FileParserFactoryInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return \App\Contract\FileParser\FileParserInterface
     *
     * @throws \App\FileParser\Exception\FileParserException
     */
    public function create(UploadedFile $file): FileParserInterface;
}
