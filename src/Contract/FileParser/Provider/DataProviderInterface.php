<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Contract\FileParser\Provider;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Data Provider Interface.
 *
 * @author wicliff <wwolda@gmail.com>
 */
interface DataProviderInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return string|null
     */
    public function provide(UploadedFile $file): ?string;
}
