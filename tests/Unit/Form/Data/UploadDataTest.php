<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Form\Data;

use App\Form\Data\UploadData;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Upload Data Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class UploadDataTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testIsValidUpload(): void
    {
        $file = new UploadedFile(__FILE__, 'foo', null, \UPLOAD_ERR_OK, true);
        $data = new UploadData();
        $data->setFile($file);

        self::assertTrue($data->isValidUpload());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testIsntValidUpload(): void
    {
        $file = new UploadedFile(__FILE__, 'foo', null, \UPLOAD_ERR_EXTENSION, true);
        $data = new UploadData();
        $data->setFile($file);

        self::assertFalse($data->isValidUpload());
        self::assertSame($file, $data->getFile());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testSetName(): void
    {
        $data = new UploadData();
        $data->setName('foo');

        self::assertSame('foo', $data->getName());
    }
}
