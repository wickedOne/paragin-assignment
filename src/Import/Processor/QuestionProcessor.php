<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Import\Processor;

use App\Contract\Import\ProcessorInterface;
use App\Entity\Question;
use App\Entity\Remindo;
use App\Import\Enum\Entity;
use App\Import\Persistence\RemindoPersistence;

/**
 * Question Processor.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class QuestionProcessor implements ProcessorInterface
{
    private const ROW_START = 1;
    private const COL_START = 1;

    private RemindoPersistence $persistence;

    /**
     * @param \App\Import\Persistence\RemindoPersistence $persistence
     */
    public function __construct(RemindoPersistence $persistence)
    {
        $this->persistence = $persistence;
    }

    /**
     * {@inheritdoc}
     */
    public function process(array $data, Remindo $remindo): Remindo
    {
        foreach (\array_slice($data[self::ROW_START], self::COL_START, null, true) as $sequence => $max) {
            $question = Question::fromImportData($sequence, (int) $max, $remindo);

            $this->persistence->persist($question);
        }

        return $this->persistence->flush($remindo);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $type): bool
    {
        return Entity::TYPE_QUESTION === $type;
    }
}
