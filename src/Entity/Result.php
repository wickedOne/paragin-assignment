<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Result.
 *
 * @ORM\Entity()
 *
 * @author wicliff <wwolda@gmail.com>
 */
class Result
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
    private \DateTime $created;

    /**
     * @ORM\Column(type="float")
     */
    private float $score;

    /**
     * @var \App\Entity\Remindo
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Remindo", inversedBy="results")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    private Remindo $remindo;

    /**
     * @var \App\Entity\Respondent
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Respondent", inversedBy="results")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    private Respondent $respondent;

    /**
     * @var \App\Entity\Question
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="results")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    private Question $question;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->created = new \DateTime();
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
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @return \App\Entity\Remindo
     */
    public function getRemindo(): Remindo
    {
        return $this->remindo;
    }

    /**
     * @return \App\Entity\Respondent
     */
    public function getRespondent(): Respondent
    {
        return $this->respondent;
    }

    /**
     * @return \App\Entity\Question
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }

    /**
     * @return float
     */
    public function getScore(): float
    {
        return $this->score;
    }

    /**
     * @param float                  $score
     * @param \App\Entity\Respondent $respondent
     * @param \App\Entity\Remindo    $remindo
     * @param \App\Entity\Question   $question
     *
     * @return self
     */
    public static function fromImportData(float $score, Respondent $respondent, Remindo $remindo, Question $question): self
    {
        $result = new self();

        $result->score = $score;
        $result->respondent = $respondent;
        $result->remindo = $remindo;
        $result->question = $question;

        return $result;
    }
}
