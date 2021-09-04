<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Validation;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * File Validator.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class FileValidator
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function validate(UploadedFile $file): bool
    {
        return $file->isValid() && is_readable($file->getPathname());
    }
}
