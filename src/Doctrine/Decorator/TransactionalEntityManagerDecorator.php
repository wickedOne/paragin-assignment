<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Doctrine\Decorator;

use App\Doctrine\Exception\DoctrineTransactionException;
use Doctrine\ORM\Decorator\EntityManagerDecorator;

/**
 * Transactional Entity Manager Decorator.
 *
 * @author wicliff <wwolda@gmail.com>
 */
final class TransactionalEntityManagerDecorator extends EntityManagerDecorator
{
    /**
     * @param mixed $entity
     */
    public function flush($entity = null): void
    {
        $this->wrapped->getConnection()->beginTransaction();

        try {
            $this->wrapped->flush();
            $this->wrapped->getConnection()->commit();
        } catch (\Exception $e) {
            $this->wrapped->getConnection()->rollBack();

            throw new DoctrineTransactionException('Transaction failed', 422, $e);
        }
    }
}
