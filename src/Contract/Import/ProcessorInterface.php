<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Contract\Import;

use App\Entity\Remindo;

/**
 * Processor Interface.
 *
 * @author wicliff <wwolda@gmail.com>
 */
interface ProcessorInterface
{
    /**
     * @param array<int|string, array> $data
     * @param \App\Entity\Remindo      $remindo
     *
     * @return \App\Entity\Remindo
     *
     * @throws \App\Doctrine\Exception\DoctrineTransactionException
     */
    public function process(array $data, Remindo $remindo): Remindo;

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supports(string $type): bool;
}
