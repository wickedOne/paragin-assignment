<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\PHPStan\Factory;

use App\Contract\FileParser\FileParserFactoryInterface;
use App\Contract\FileParser\FileParserInterface;
use App\FileParser\FileParserFactory;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * FileParser Instantiation Rule.
 *
 * @author wicliff <wwolda@gmail.com>
 *
 * @implements \PHPStan\Rules\Rule<Node\Expr\New_>
 */
class FileParserInstantiationRule implements Rule
{
    /**
     * {@inheritDoc}
     */
    public function getNodeType(): string
    {
        return Node\Expr\New_::class;
    }

    /**
     * {@inheritDoc}
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->class instanceof Node\Name) {
            return [];
        }

        if (!is_subclass_of($node->class->toString(), FileParserInterface::class)) {
            return [];
        }

        if (
            $scope->isInClass()
            && $scope->getClassReflection()->implementsInterface(FileParserFactoryInterface::class)
        ) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf('FileParsers must be instantiated through the %s', FileParserFactory::class)
            )->build(),
        ];
    }
}
