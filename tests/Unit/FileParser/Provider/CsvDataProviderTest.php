<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\FileParser\Provider;

use App\FileParser\Provider\CsvDataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Csv DataProvider Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class CsvDataProviderTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testProvideString(): void
    {
        $file = new UploadedFile(__FILE__, 'foo');

        self::assertIsString((new CsvDataProvider())->provide($file));
    }
}
