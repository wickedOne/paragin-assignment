<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Import\Persistence;

use App\Doctrine\Exception\DoctrineTransactionException;
use App\Entity\Remindo;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Remindo Persistence.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class RemindoPersistence
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface|\App\Doctrine\Decorator\TransactionalEntityManagerDecorator
     */
    private EntityManagerInterface $em;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param \Psr\Log\LoggerInterface             $logger
     */
    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * @param object $object
     */
    public function persist(object $object): void
    {
        $this->em->persist($object);
    }

    /**
     * @param \App\Entity\Remindo $remindo
     *
     * @return \App\Entity\Remindo
     *
     * @throws \App\Doctrine\Exception\DoctrineTransactionException
     */
    public function flush(Remindo $remindo): Remindo
    {
        try {
            $this->em->flush();
        } catch (DoctrineTransactionException $e) {
            $this->logger->error(
                'flushing processed entities for {test} failed. aborting import. {message}',
                [
                    'test' => $remindo->getName(),
                    'message' => $e->getMessage(),
                    'exception' => $e,
                ]
            );

            throw $e;
        }

        $this->em->clear();

        return $this->em->getRepository(Remindo::class)->getEager($remindo->getId());
    }
}
