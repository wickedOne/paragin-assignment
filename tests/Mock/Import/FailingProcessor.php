<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Mock\Import;

use App\Contract\Import\ProcessorInterface;
use App\Doctrine\Exception\DoctrineTransactionException;
use App\Entity\Remindo;

/**
 * Failing Processor.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class FailingProcessor implements ProcessorInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(array $data, Remindo $remindo): Remindo
    {
        throw new DoctrineTransactionException('transaction failed');
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return true;
    }
}
