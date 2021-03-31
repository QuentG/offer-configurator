<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OrderItemRepository::class)
 */
class OrderItem
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="orderItems")
     * @Groups({"order.read"})
     */
    private ?Product $product = null;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"order.read"})
     */
    private int $quantity = 1;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="items")
     * @ORM\JoinColumn(nullable=true)
     */
    private Order $orderRef;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOrderRef(): Order
    {
        return $this->orderRef;
    }

    public function setOrderRef(Order $orderRef): self
    {
        $this->orderRef = $orderRef;

        return $this;
    }

    /**
     * Tests if the given item given corresponds to the same order item.
     */
    public function equals(OrderItem $item): bool
    {
        return $this->getProduct()?->getId() === $item->getProduct()?->getId();
    }

    /**
     * Calculates the item total.
     */
    public function getTotal(): float|int
    {
        return $this->getProduct()?->getPrice() * $this->getQuantity();
    }
}
