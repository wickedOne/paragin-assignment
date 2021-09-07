<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Doctrine\Decorator;

use App\Doctrine\Decorator\TransactionalEntityManagerDecorator;
use App\Doctrine\Exception\DoctrineTransactionException;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Transactional EntityManager Decorator Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class TransactionalEntityManagerDecoratorTest extends TestCase
{
    /**
     * @throws \App\Doctrine\Exception\DoctrineTransactionException
     */
    public function testFlush(): void
    {
        $em = $this->getEntityManager(0);
        $decorator = new TransactionalEntityManagerDecorator($em);

        $decorator->flush();
    }

    /**
     * @throws \App\Doctrine\Exception\DoctrineTransactionException
     */
    public function testFailedFlush(): void
    {
        $this->expectException(DoctrineTransactionException::class);

        $em = $this->getEntityManager(1);
        $decorator = new TransactionalEntityManagerDecorator($em);

        $decorator->flush();
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testFailedFlushException(): void
    {
        $em = $this->getEntityManager(1);
        $decorator = new TransactionalEntityManagerDecorator($em);

        try {
            $decorator->flush();
        } catch (DoctrineTransactionException $e) {
            self::assertSame(422, $e->getCode());
        }
    }

    /**
     * @param int $rollbackCount
     *
     * @return \Doctrine\ORM\EntityManagerInterface
     */
    private function getEntityManager(int $rollbackCount): EntityManagerInterface
    {
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $connection
            ->expects($this->once())
            ->method('beginTransaction')
        ;

        if (0 === $rollbackCount) {
            $connection
                ->expects($this->once())
                ->method('commit');
        } else {
            $connection
                ->expects($this->once())
                ->method('commit')
                ->will(self::throwException(new DoctrineTransactionException('foo', 0)))
            ;
        }

        $connection
            ->expects($this->exactly($rollbackCount))
            ->method('rollBack')
        ;

        $em = $this->getMockBuilder(EntityManagerInterface::class)->disableOriginalConstructor()->getMock();

        $em->expects($this->any())
            ->method('getConnection')
            ->willReturn($connection)
        ;

        $em
            ->expects($this->once())
            ->method('flush')
        ;

        return $em;
    }
}
