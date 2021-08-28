<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\FileParser;

use App\Exception\FileParseException;
use SimpleXLSX;

/**
 * Xlsx File Parser.
 *
 * @author wicliff <wwolda@gmail.com>
 */
final class XlsxFileParser extends AbstractFileParser
{
    /**
     * {@inheritdoc}
     */
    public function parse(): array
    {
        if (false === $this->validate()) {
            throw new FileParseException(sprintf('invalid file %s', $this->file->getFilename()));
        }

        if (false === $data = SimpleXLSX::parseData($this->getData())) {
            throw new FileParseException(sprintf('unable to parse %s', $this->file->getFilename()));
        }

        if (false === $rows = $data->rows()) {
            throw new FileParseException(sprintf('%s contains no worksheet', $this->file->getFilename()));
        }

        return $rows;
    }
}
