<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrizeTypeRepository")
 */
class PrizeType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $range_min;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $range_max;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lottery")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lottery;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRangeMin(): ?int
    {
        return $this->range_min;
    }

    public function setRangeMin(?int $range_min): self
    {
        $this->range_min = $range_min;

        return $this;
    }

    public function getRangeMax(): ?int
    {
        return $this->range_max;
    }

    public function setRangeMax(?int $range_max): self
    {
        $this->range_max = $range_max;

        return $this;
    }

    public function getLottery(): ?Lottery
    {
        return $this->lottery;
    }

    public function setLottery(?Lottery $lottery): self
    {
        $this->lottery = $lottery;

        return $this;
    }
}
