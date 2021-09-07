<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Import\Persistence;

use App\Doctrine\Exception\DoctrineTransactionException;
use App\Entity\Remindo;
use App\Import\Persistence\RemindoPersistence;
use App\Repository\RemindoRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Remindo Persistence Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class RemindoPersistenceTest extends TestCase
{
    /**
     * Test persist.
     */
    public function testPersist()
    {
        $persistence = new RemindoPersistence($this->getEntityManager(1, 0), $this->getLogger(0));

        $persistence->persist(new Remindo());
    }

    /**
     * @throws \App\Doctrine\Exception\DoctrineTransactionException
     */
    public function testFlushSuccess(): void
    {
        $persistence = new RemindoPersistence($this->getEntityManager(0, 1), $this->getLogger(0));

        $persistence->flush((new Remindo())->setName('foo'));
    }

    /**
     * @throws \App\Doctrine\Exception\DoctrineTransactionException
     */
    public function testFlushError(): void
    {
        $this->expectException(DoctrineTransactionException::class);

        $logger = $this->getLogger(1, 'flushing processed entities for {test} failed. aborting import. {message}', ['test', 'message', 'exception']);
        $persistence = new RemindoPersistence($this->getEntityManager(0, 1, true), $logger);

        $persistence->flush((new Remindo())->setName('foo'));
    }

    /**
     * @param int  $persist
     * @param int  $flush
     * @param bool $throw
     *
     * @return \Doctrine\ORM\EntityManagerInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getEntityManager(int $persist, int $flush, bool $throw = false)
    {
        $em = $this->getMockBuilder(EntityManagerInterface::class)->disableOriginalConstructor()->getMock();

        $em
            ->expects($this->exactly($persist))
            ->method('persist')
        ;

        if (true === $throw) {
            $em
                ->expects($this->exactly($flush))
                ->method('flush')
                ->will(self::throwException(new DoctrineTransactionException()));

            return $em;
        }

        $em
            ->expects($this->exactly($flush))
            ->method('flush')
        ;

        $em
            ->expects($this->exactly($flush))
            ->method('clear')
        ;

        $repo = $this->getMockBuilder(RemindoRepository::class)->disableOriginalConstructor()->getMock();
        $repo
            ->expects($this->exactly($flush > 0 ? 1 : 0))
            ->method('getEager')
            ->willReturn(new Remindo())
        ;

        $em
            ->expects(self::exactly($flush))
            ->method('getRepository')
            ->with(Remindo::class)
            ->willReturn($repo)
        ;

        return $em;
    }

    /**
     * @param int         $log
     * @param string|null $message
     * @param array       $parameters
     *
     * @return mixed|\PHPUnit\Framework\MockObject\MockObject|\Psr\Log\LoggerInterface
     */
    private function getLogger(int $log, string $message = null, array $parameters = [])
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $logger->expects($this->exactly($log))
            ->method('error')
        ;

        if (0 !== $log) {
            $logger
                ->expects(self::exactly($log))
                ->method('error')
                ->with(
                    $message,
                    $this->callback(fn ($subject) => array_keys($subject) === $parameters)
                )
            ;
        }

        return $logger;
    }
}
