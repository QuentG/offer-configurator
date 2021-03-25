<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\AttributeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AttributeRepository::class)
 */
class Attribute
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"offer.read"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"offer.read"})
     */
    private string $label = '';

    /**
     * @ORM\ManyToOne(targetEntity=Option::class, inversedBy="attributes", cascade={"persist"})
     */
    private ?Option $option;

    /**
     * @ORM\Column(type="float")
     * @Groups({"offer.read"})
     */
    private float $price = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getOption(): ?Option
    {
        return $this->option;
    }

    public function setoption(?Option $option): self
    {
        $this->option = $option;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function __toString(): string
    {
        return $this->label;
    }
}
