<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\FileParser;

use App\Contract\FileParser\FileParserInterface;
use App\Contract\FileParser\Provider\DataProviderInterface;
use App\Exception\FileParser\FileParseException;
use App\FileParser\Provider\XlsxDataProvider;
use App\Validation\FileValidator;
use SimpleXLSX;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Xlsx File Parser.
 *
 * @author wicliff <wwolda@gmail.com>
 */
final class XlsxFileParser implements FileParserInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    private UploadedFile $file;

    /**
     * @var \App\Validation\FileValidator
     */
    private FileValidator $validator;

    /**
     * @var \App\Contract\FileParser\Provider\DataProviderInterface
     */
    private DataProviderInterface $provider;

    /**
     * @var \SimpleXLSX
     */
    private SimpleXLSX $simpleXlsx;

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile          $file
     * @param \App\Validation\FileValidator|null                           $validator
     * @param \App\Contract\FileParser\Provider\DataProviderInterface|null $provider
     * @param \SimpleXLSX|null                                             $simpleXLSX
     *
     * @throws \App\Exception\FileParser\FileParseException
     */
    public function __construct(UploadedFile $file, FileValidator $validator = null, DataProviderInterface $provider = null, SimpleXLSX $simpleXLSX = null)
    {
        $this->file = $file;
        $this->validator = $validator ?: new FileValidator();
        $this->provider = $provider ?: new XlsxDataProvider();
        $this->simpleXlsx = $simpleXLSX ?: new SimpleXLSX($this->getData(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function parse(): array
    {
        if (false === $this->validate()) {
            throw new FileParseException(sprintf('invalid file %s', $this->file->getFilename()));
        }

        if (false === $rows = $this->simpleXlsx->rows()) {
            throw new FileParseException(sprintf('unable to parse %s', $this->file->getFilename()));
        }

        return $rows;
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
