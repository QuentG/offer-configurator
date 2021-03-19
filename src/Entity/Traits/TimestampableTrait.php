<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait TimestampableTrait
{
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    protected ?\DateTimeImmutable $createdAt = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?\DateTimeInterface $updatedAt = null;

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
