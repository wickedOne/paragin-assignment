<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Contract\FileParser;

/**
 * FileParser Interface.
 *
 * @author wicliff <wwolda@gmail.com>
 */
interface FileParserInterface
{
    /**
     * @return bool
     */
    public function validate(): bool;

    /**
     * @return array<int, array>
     *
     * @throws \App\FileParser\Exception\FileParserException
     */
    public function parse(): array;

    /**
     * @return string|null
     *
     * @throws \App\FileParser\Exception\FileParserException
     */
    public function getData(): ?string;
}
