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
 * Abstract File Parser.
 *
 * @author wicliff <wwolda@gmail.com>
 */
abstract class AbstractFileParser
{
    /**
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    protected UploadedFile $file;

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * @throws \App\Exception\FileParseException
     *
     * @return array<int, array>
     */
    abstract public function parse(): array;

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return $this->file->isValid() && is_readable($this->file->getPathname());
    }

    /**
     * @return string
     *
     * @throws \App\Exception\FileParseException
     */
    protected function getData(): string
    {
        if (false === $upload = fopen($this->file->getPathname(), 'rb')) {
            throw new FileParseException(sprintf('failed to upload %s', $this->file->getPathname()));
        }

        if (false === $data = fread($upload, $this->file->getSize())) {
            throw new FileParseException(sprintf('failed to read %s', $this->file->getPathname()));
        }

        return $data;
    }
}
