<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Import\Processor;

use App\Contract\Import\ProcessorInterface;
use App\Entity\Remindo;
use App\Entity\Respondent;
use App\Import\Enum\Entity;
use App\Import\Persistence\RemindoPersistence;

/**
 * Respondent Processor.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class RespondentProcessor implements ProcessorInterface
{
    private const ROW_START = 2;
    private const COL_START = 0;

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
        $count = \count($data);

        for ($respondentRow = self::ROW_START; $respondentRow < $count; ++$respondentRow) {
            $respondent = Respondent::fromImportData($data[$respondentRow][self::COL_START], $remindo);

            $this->persistence->persist($respondent);
        }

        return $this->persistence->flush($remindo);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $type): bool
    {
        return Entity::TYPE_RESPONDENT === $type;
    }
}
