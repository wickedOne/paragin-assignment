<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\PHPStan\Factory;

use App\FileParser\FileParserFactory;
use App\PHPStan\Factory\FileParserInstantiationRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * FileParser Instantiation Rule Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class FileParserInstantiationRuleTest extends RuleTestCase
{
    /**
     * test FileParserFactory rule.
     */
    public function testRule(): void
    {
        $this->analyse([
            __DIR__.'/../../Mock/PHPStan/Factory/FileParser/FileParserInstantiation.php',
            __DIR__.'/../../Mock/PHPStan/Factory/FileParser/FileParserFactoryInstantiation.php',
            ], [
            [
                sprintf('FileParsers must be instantiated through the %s', FileParserFactory::class),
                28,
            ],
        ]);
    }

    /**
     * @return \PHPStan\Rules\Rule
     */
    protected function getRule(): Rule
    {
        return new FileParserInstantiationRule();
    }
}
