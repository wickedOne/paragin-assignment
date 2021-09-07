<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\Enum;

use App\Import\Enum\Entity;
use PHPUnit\Framework\TestCase;

/**
 * Entity Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class EntityTest extends TestCase
{
    /**
     * making sure remindo entity is always prcessed first.
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testRemindoComesFirst(): void
    {
        self::assertSame(Entity::TYPE_REMINDO, Entity::TYPES[0]);
    }
}
