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
 * Csv Data Provider.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class CsvDataProvider implements DataProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function provide(UploadedFile $file): ?string
    {
        return file_get_contents($file->getPathname()) ?: null;
    }
}
