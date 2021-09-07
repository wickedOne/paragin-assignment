<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Entity;

use App\Util\CeasuraUtil;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Respondent.
 *
 * @ORM\Entity()
 *
 * @author wicliff <wwolda@gmail.com>
 */
class Respondent
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
     * @var \App\Entity\Remindo
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Remindo", inversedBy="respondents")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    private Remindo $remindo;

    /**
     * @var Collection<int, \App\Entity\Result>
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Result", mappedBy="respondent")
     */
    private Collection $results;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->created = new \DateTime();
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
    public function getCeasura(): float
    {
        return CeasuraUtil::calculate($this->getSumScore(), $this->getSumMax());
    }

    /**
     * @param string              $name
     * @param \App\Entity\Remindo $remindo
     *
     * @return self
     */
    public static function fromImportData(string $name, Remindo $remindo): self
    {
        $respondent = new self();

        $respondent->name = $name;
        $respondent->remindo = $remindo;

        return $respondent;
    }

    /**
     * @return float
     */
    private function getSumScore(): float
    {
        return (float) array_reduce($this->results->toArray(), static function ($carry, Result $result) {
            return $carry + $result->getScore();
        });
    }

    /**
     * @return int
     */
    private function getSumMax(): int
    {
        return (int) array_reduce($this->results->toArray(), static function ($carry, Result $result) {
            return $carry + $result->getQuestion()->getMax();
        });
    }
}
