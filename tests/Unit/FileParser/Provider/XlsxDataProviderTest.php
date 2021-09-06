<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\FileParser\Provider;

use App\FileParser\Provider\XlsxDataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Xlsx DataProvider Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class XlsxDataProviderTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testProvide(): void
    {
        $file = new UploadedFile(__DIR__.'/../../../Mock/books.xlsx', 'foo');

        self::assertIsString((new XlsxDataProvider())->provide($file));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testProvideNull(): void
    {
        $file = $this->getMockBuilder(UploadedFile::class)->disableOriginalConstructor()->onlyMethods(['getPathname'])->getMock();
        $file->expects($this->once())
            ->method('getPathname')
            ->willReturn('foo')
        ;

        self::assertNull((new XlsxDataProvider())->provide($file));
    }
}
