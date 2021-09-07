<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Import\Processor;

use App\Contract\Import\ProcessorInterface;
use App\Entity\Remindo;
use App\Entity\Result;
use App\Import\Enum\Entity;
use App\Import\Persistence\RemindoPersistence;

/**
 * Result Processor.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class ResultProcessor implements ProcessorInterface
{
    private const ROW_START = 2;
    private const COL_START = 1;

    private const BATCH_SIZE = 50;

    private RemindoPersistence $persistence;

    /**
     * @param \App\Import\Persistence\RemindoPersistence $persistence
     */
    public function __construct(RemindoPersistence $persistence)
    {
        $this->persistence = $persistence;
    }

    /**
     * {@inheritDoc}
     */
    public function process(array $data, Remindo $remindo): Remindo
    {
        $i = 0;

        foreach ($this->respondentGenerator($data) as $name => $results) {
            if (null === $respondent = $remindo->getRespondentByName($name)) {
                continue;
            }

            foreach ($this->resultGenerator($results) as $sequence => $score) {
                if (null === $question = $remindo->getQuestionBySequence($sequence)) {
                    continue;
                }

                $result = Result::fromImportData((float) $score, $respondent, $remindo, $question);

                $this->persistence->persist($result);
            }

            if ((++$i % self::BATCH_SIZE) === 0) {
                $remindo = $this->persistence->flush($remindo);
            }
        }

        return $this->persistence->flush($remindo);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $type): bool
    {
        return Entity::TYPE_RESULT === $type;
    }

    /**
     * @param array<int|string, array> $data
     *
     * @return \Generator<string, array>
     */
    private function respondentGenerator(array $data): \Generator
    {
        foreach (\array_slice($data, self::ROW_START) as $respondent) {
            yield array_shift($respondent) => $respondent;
        }
    }

    /**
     * @param array<int, float> $results
     *
     * @return \Generator<int, float>
     */
    private function resultGenerator(array $results): \Generator
    {
        foreach (\array_slice($results, self::COL_START, null, true) as $sequence => $score) {
            yield $sequence => $score;
        }
    }
}
