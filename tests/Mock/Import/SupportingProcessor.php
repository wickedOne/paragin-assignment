<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Mock\Import;

use App\Contract\Import\ProcessorInterface;
use App\Entity\Remindo;

/**
 * SupportingProcessor.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class SupportingProcessor implements ProcessorInterface
{
    private ?string $name;

    /**
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function process(array $data, Remindo $remindo): Remindo
    {
        return $remindo->setName($this->name ?: $remindo->getName());
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return true;
    }
}
