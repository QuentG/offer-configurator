<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    use TimestampableTrait;

    public const PARENT_TYPE = 'configurable';
    public const CHILD_TYPE = 'simple';

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
    private string $name = '';

    /**
     * @ORM\Column(type="text", length=255)
     * @Groups({"offer.read"})
     */
    private string $description = '';

    /**
     * @ORM\Column(type="bigint")
     * @Groups({"offer.read"})
     */
    private string $stock = '';

    /**
     * @ORM\Column(type="float")
     * @Groups({"offer.read"})
     */
    private float $price = 0;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"offer.read"})
     */
    private string $brand = '';

    /**
     * @ORM\ManyToMany(targetEntity=Offer::class, mappedBy="products")
     * @Groups({"offer.read"})
     */
    private Collection $offers;

    /**
     * @ORM\ManyToMany(targetEntity=Option::class, mappedBy="products", cascade={"persist"})
     * @Groups({"offer.read"})
     */
    private Collection $options;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $type = '';

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $parentId = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $entityId = null;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $optionSelected = [];

    public function __construct()
    {
        $this->offers = new ArrayCollection();
        $this->options = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStock(): string
    {
        return $this->stock;
    }

    public function setStock(string $stock): self
    {
        $this->stock = $stock;

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

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->addProduct($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            $offer->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->addProduct($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->removeElement($option)) {
            $option->removeProduct($this);
        }

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getParentId()
    {
        return $this->parentId;
    }

    public function setParentId($parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    public function setEntityId(int $entityId): self
    {
        $this->entityId = $entityId;

        return $this;
    }

    public function getFinalPrice()
    {
        $discounts = [];
        foreach($this->offers as $offer) {
            $discounts[] = $offer->getPrice();
        }
        return round(array_sum($discounts), 2);
    }

    public function getOptionSelected(): ?array
    {
        return $this->optionSelected;
    }

    public function setOptionSelected(?array $optionSelected): self
    {
        $this->optionSelected = $optionSelected;

        return $this;
    }
}
