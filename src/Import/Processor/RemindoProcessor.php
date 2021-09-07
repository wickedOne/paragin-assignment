<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Import\Processor;

use App\Contract\Import\ProcessorInterface;
use App\Entity\Remindo;
use App\Import\Enum\Entity;
use App\Import\Persistence\RemindoPersistence;

/**
 * Remindo Processor.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class RemindoProcessor implements ProcessorInterface
{
    /**
     * @var \App\Import\Persistence\RemindoPersistence
     */
    private RemindoPersistence $persistence;

    /**
     * @param \App\Import\Persistence\RemindoPersistence $persistence
     */
    public function __construct(RemindoPersistence $persistence)
    {
        $this->persistence = $persistence;
    }

    /**
     * {@inheritdoc}
     */
    public function process(array $data, Remindo $remindo): Remindo
    {
        $this->persistence->persist($remindo);

        return $this->persistence->flush($remindo);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $type): bool
    {
        return Entity::TYPE_REMINDO === $type;
    }
}
