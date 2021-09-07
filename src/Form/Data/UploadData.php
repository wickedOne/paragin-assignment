<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Form\Data;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UploadData.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class UploadData
{
    /**
     * @var string
     *
     * @Assert\NotNull()
     */
    private string $name;

    /**
     * @var UploadedFile
     *
     * @Assert\NotNull()
     * @Assert\File()
     */
    private UploadedFile $file;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return UploadedFile
     */
    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setFile(UploadedFile $file): void
    {
        $this->file = $file;
    }

    /**
     * @return bool
     */
    public function isValidUpload(): bool
    {
        return $this->getFile()->isValid() && is_readable($this->getFile()->getPathname());
    }
}
