<?php

namespace Goksagun\ApiBundle\Entity\Util;

use Doctrine\ORM\Mapping\MappedSuperclass;

#[MappedSuperclass]
trait UpdatedTimestampTrait
{

    #[\Doctrine\ORM\Mapping\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[\Doctrine\ORM\Mapping\PreUpdate()]
    public function setUpdatedTimestamp(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}