<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests;

/**
 * Util.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class Util
{
    /**
     * @param object $object
     * @param string $property
     * @param $value
     */
    public static function setPrivateProperty(object $object, string $property, $value): void
    {
        $refClass = new \ReflectionClass($object);

        $prop = $refClass->getProperty($property);
        $prop->setAccessible(true);
        $prop->setValue($object, $value);
    }
}
