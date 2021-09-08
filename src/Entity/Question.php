<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Entity;

use App\Util\PValueUtil;
use App\Util\RValueUtil;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Question.
 *
 * @ORM\Entity()
 *
 * @author wicliff <wwolda@gmail.com>
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidInterface $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private DateTime $created;

    /**
     * @ORM\Column(type="integer")
     */
    private int $sequence;

    /**
     * @ORM\Column(type="integer")
     */
    private int $max;

    /**
     * @var \App\Entity\Remindo
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Remindo", inversedBy="questions")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    private Remindo $remindo;

    /**
     * @var Collection<int, \App\Entity\Result>
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Result", mappedBy="question")
     */
    private Collection $results;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->created = new DateTime();
        $this->results = new ArrayCollection();
    }

    /**
     * @infection-ignore-all
     *
     * @codeCoverageIgnore
     *
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @infection-ignore-all
     *
     * @codeCoverageIgnore
     *
     * @return \DateTime
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * @return int
     */
    public function getSequence(): int
    {
        return $this->sequence;
    }

    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * @return \App\Entity\Remindo
     */
    public function getRemindo(): Remindo
    {
        return $this->remindo;
    }

    /**
     * @return Collection<int, \App\Entity\Result>
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    /**
     * @return float
     */
    public function getPValue(): float
    {
        if ($this->results->isEmpty()) {
            return 0.0;
        }

        $sum = array_reduce($this->results->toArray(), static function ($carry, Result $result): float {
            $carry += $result->getScore();

            return $carry;
        });

        return PValueUtil::calculate(($sum ?? 0 / $this->results->count()), $this->max);
    }

    /**
     * @return float
     */
    public function getRValue(): float
    {
        if ($this->results->isEmpty()) {
            return 0.0;
        }

        $scores = array_map(static function (Result $result): float {
            return $result->getScore();
        }, $this->results->toArray());

        $grades = array_map(static function (Result $result): float {
            return $result->getRespondent()->getCeasura();
        }, $this->results->toArray());

        return RValueUtil::calculate($scores, $grades);
    }

    /**
     * @param int                 $sequence
     * @param int                 $max
     * @param \App\Entity\Remindo $remindo
     *
     * @return self
     */
    public static function fromImportData(int $sequence, int $max, Remindo $remindo): self
    {
        $question = new self();

        $question->sequence = $sequence;
        $question->max = $max;
        $question->remindo = $remindo;

        return $question;
    }
}
