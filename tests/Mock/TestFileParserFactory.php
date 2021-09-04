<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Mock;

use App\Contract\FileParser\FileParserFactoryInterface;
use App\Contract\FileParser\FileParserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Test FileParser Factory.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class TestFileParserFactory implements FileParserFactoryInterface
{
    private bool $valid;
    private ?string $data;
    private array $parsed;

    /**
     * @param bool        $valid
     * @param string|null $data
     * @param array       $parsed
     */
    public function __construct(bool $valid, ?string $data, array $parsed = [])
    {
        $this->valid = $valid;
        $this->data = $data;
        $this->parsed = $parsed;
    }

    /**
     * {@inheritDoc}
     */
    public function create(UploadedFile $file): FileParserInterface
    {
        return new TestCsvFileParser($this->valid, $this->data, $this->parsed);
    }
}
