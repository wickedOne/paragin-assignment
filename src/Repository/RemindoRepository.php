<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Repository;

use App\Entity\Remindo;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\UuidInterface;

/**
 * Remindo Repository.
 *
 * @author wicliff <wwolda@gmail.com>
 *
 * @extends \Doctrine\ORM\EntityRepository<Remindo>
 */
final class RemindoRepository extends EntityRepository
{
    /**
     * @param UuidInterface $id
     *
     * @return \App\Entity\Remindo
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getEager(UuidInterface $id): Remindo
    {
        return $this->createQueryBuilder('remindo')
            ->select(['remindo', 'questions'])
            ->leftJoin('remindo.questions', 'questions')
            ->andWhere('remindo.id = :id')
            ->setParameters([
                'id' => $id,
            ])
            ->getQuery()
            ->getSingleResult()
        ;
    }
}
