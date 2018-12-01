<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 */
class Address
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
    private $city;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $steet;

    /**
     * @ORM\Column(type="integer")
     */
    private $house;

    /**
     * @ORM\Column(type="integer")
     */
    private $flat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getSteet(): ?string
    {
        return $this->steet;
    }

    public function setSteet(string $steet): self
    {
        $this->steet = $steet;

        return $this;
    }

    public function getHouse(): ?int
    {
        return $this->house;
    }

    public function setHouse(int $house): self
    {
        $this->house = $house;

        return $this;
    }

    public function getFlat(): ?int
    {
        return $this->flat;
    }

    public function setFlat(int $flat): self
    {
        $this->flat = $flat;

        return $this;
    }
}
