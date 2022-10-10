<?php

namespace App\Entity;

use App\Repository\AddressesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressesRepository::class)]
class Addresses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $number_of_street = null;

    #[ORM\Column(length: 255)]
    private ?string $name_of_street = null;

    #[ORM\Column(length: 255)]
    private ?string $typeOfWay = null;

    #[ORM\Column(length: 255)]
    private ?string $PostalCode = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $Department = null;

    #[ORM\Column(length: 255)]
    private ?string $Region = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategAdress $categAdress = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNumberOfStreet(): ?string
    {
        return $this->number_of_street;
    }

    public function setNumberOfStreet(string $number_of_street): self
    {
        $this->number_of_street = $number_of_street;

        return $this;
    }

    public function getNameOfStreet(): ?string
    {
        return $this->name_of_street;
    }

    public function setNameOfStreet(string $name_of_street): self
    {
        $this->name_of_street = $name_of_street;

        return $this;
    }

    public function getTypeOfWay(): ?string
    {
        return $this->typeOfWay;
    }

    public function setTypeOfWay(string $typeOfWay): self
    {
        $this->typeOfWay = $typeOfWay;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->PostalCode;
    }

    public function setPostalCode(string $PostalCode): self
    {
        $this->PostalCode = $PostalCode;

        return $this;
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

    public function getDepartment(): ?string
    {
        return $this->Department;
    }

    public function setDepartment(string $Department): self
    {
        $this->Department = $Department;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->Region;
    }

    public function setRegion(string $Region): self
    {
        $this->Region = $Region;

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

    public function getCategAdress(): ?CategAdress
    {
        return $this->categAdress;
    }

    public function setCategAdress(?CategAdress $categAdress): self
    {
        $this->categAdress = $categAdress;

        return $this;
    }
}
