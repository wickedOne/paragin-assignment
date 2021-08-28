<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Remindo Test.
 *
 * @ORM\Entity(repositoryClass="App\Repository\RemindoRepository")
 *
 * @author wicliff <wwolda@gmail.com>
 */
class Remindo
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
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @var Collection<int, \App\Entity\Respondent>
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Respondent", mappedBy="remindo", cascade={"persist"})
     * @ORM\OrderBy({"name" = "asc"})
     */
    private Collection $respondents;

    /**
     * @var Collection<int, \App\Entity\Result>
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Result", mappedBy="remindo", cascade={"persist"})
     */
    private Collection $results;

    /**
     * @var Collection<int, \App\Entity\Question>
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="remindo", cascade={"persist"})
     * @ORM\OrderBy({"sequence" = "asc"})
     */
    private Collection $questions;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->created = new \DateTime();
        $this->respondents = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->results = new ArrayCollection();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return \App\Entity\Remindo
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, \App\Entity\Respondent>
     */
    public function getRespondents(): Collection
    {
        return $this->respondents;
    }

    /**
     * @return Collection<int, \App\Entity\Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    /**
     * @param int $sequence
     *
     * @return false|\App\Entity\Question
     */
    public function getQuestionBySequence(int $sequence)
    {
        if (!$this->questions instanceof Selectable) {
            return false;
        }

        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->eq('sequence', $sequence))
        ;

        return $this->questions->matching($criteria)->first();
    }

    /**
     * @return Collection<int, \App\Entity\Result>
     */
    public function getResults(): Collection
    {
        return $this->results;
    }
}
