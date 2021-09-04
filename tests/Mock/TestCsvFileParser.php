<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Mock;

use App\Contract\FileParser\FileParserInterface;

/**
 * Test Csv FileParser.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class TestCsvFileParser implements FileParserInterface
{
    /**
     * @var bool
     */
    private bool $valid;

    /**
     * @var string|null
     */
    private ?string $data;

    /**
     * @var array
     */
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
    public function validate(): bool
    {
        return $this->valid;
    }

    /**
     * {@inheritDoc}
     */
    public function parse(): array
    {
        return $this->parsed;
    }

    /**
     * {@inheritDoc}
     */
    public function getData(): ?string
    {
        return $this->data;
    }
}
