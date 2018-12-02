<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrizeRepository")
 */
class Prize
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lottery")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lottery;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PrizeType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $prizeType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PrizeItem")
     * @ORM\JoinColumn(nullable=true)
     */
    private $prizeItem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prize_sum;

    /**
     * @ORM\Column(type="datetime")
     */
    private $prize_date;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $send_date;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $reject_flag;

    public function getRejectFlag(): ?bool
    {
        return $this->reject_flag;
    }

    public function setRejectFlag(?bool $reject_flag): self
    {
        $this->reject_flag = $reject_flag;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrizeSum(): ?int
    {
        return $this->prize_sum;
    }

    public function setPrizeSum(?int $prize_sum): self
    {
        $this->prize_sum = $prize_sum;

        return $this;
    }

    public function getPrizeDate(): ?\DateTimeInterface
    {
        return $this->prize_date;
    }

    public function setPrizeDate(\DateTimeInterface $prize_date): self
    {
        $this->prize_date = $prize_date;

        return $this;
    }

    public function getSendDate(): ?\DateTimeInterface
    {
        return $this->send_date;
    }

    public function setSendDate(?\DateTimeInterface $send_date): self
    {
        $this->send_date = $send_date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPrizeType(): ?PrizeType
    {
        return $this->prizeType;
    }

    public function setPrizeType(?PrizeType $prizeType): self
    {
        $this->prizeType = $prizeType;

        return $this;
    }

    public function getPrizeItem(): ?PrizeItem
    {
        return $this->prizeItem;
    }

    public function setPrizeItem(?PrizeItem $prizeItem): self
    {
        $this->prizeItem = $prizeItem;

        return $this;
    }
}
