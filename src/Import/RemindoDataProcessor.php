<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Import;

use App\Contract\Import\ProcessorInterface;
use App\Entity\Remindo;
use App\Import\Enum\Entity;

/**
 * Remindo Data Processor.
 *
 * @author wicliff <wwolda@gmail.com>
 */
final class RemindoDataProcessor
{
    /**
     * @var \App\Contract\Import\ProcessorInterface[]
     */
    private array $processors;

    /**
     * @var \App\Entity\Remindo
     */
    private Remindo $remindo;

    /**
     * @var array<int, array>
     */
    private array $data;

    /**
     * @param \App\Contract\Import\ProcessorInterface[] $processors
     */
    public function __construct(iterable $processors)
    {
        foreach ($processors as $processor) {
            $this->addProcessor($processor);
        }
    }

    /**
     * @param \App\Entity\Remindo $remindo
     *
     * @return $this
     */
    public function withRemindo(Remindo $remindo): self
    {
        $this->remindo = $remindo;

        return $this;
    }

    /**
     * @param array<int, array> $data
     *
     * @return $this
     */
    public function withData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return \App\Entity\Remindo
     *
     * @throws \App\Doctrine\Exception\DoctrineTransactionException
     */
    public function process(): Remindo
    {
        foreach (Entity::TYPES as $type) {
            foreach ($this->processors as $processor) {
                if (false === $processor->supports($type)) {
                    continue;
                }

                $this->remindo = $processor->process($this->data, $this->remindo);
            }
        }

        return $this->remindo;
    }

    /**
     * @param \App\Contract\Import\ProcessorInterface $processor
     */
    private function addProcessor(ProcessorInterface $processor): void
    {
        $this->processors[\get_class($processor)] = $processor;
    }
}
