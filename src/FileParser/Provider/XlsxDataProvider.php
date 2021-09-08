<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\FileParser\Provider;

use App\Contract\FileParser\Provider\DataProviderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Xlsx DataProvider.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class XlsxDataProvider implements DataProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function provide(UploadedFile $file): ?string
    {
        try {
            if (false === $upload = fopen($file->getPathname(), 'rb')) {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }

        return false !== ($string = fread($upload, $file->getSize())) ? $string : null;
    }
}
