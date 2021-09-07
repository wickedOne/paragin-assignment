<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Import\Enum;

/**
 * Entity enum.
 *
 * @author wicliff <wwolda@gmail.com>
 */
final class Entity
{
    public const TYPE_REMINDO = 'TYPE_REMINDO';
    public const TYPE_QUESTION = 'TYPE_QUESTION';
    public const TYPE_RESPONDENT = 'TYPE_RESPONDENT';
    public const TYPE_RESULT = 'TYPE_RESULT';

    public const TYPES = [
        self::TYPE_REMINDO,
        self::TYPE_QUESTION,
        self::TYPE_RESPONDENT,
        self::TYPE_RESULT,
    ];

    /**
     * Not instantiable.
     */
    private function __construct()
    {
    }
}
