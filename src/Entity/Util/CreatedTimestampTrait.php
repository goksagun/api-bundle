<?php

namespace Goksagun\ApiBundle\Entity\Util;

use Doctrine\ORM\Mapping\MappedSuperclass;

#[MappedSuperclass]
trait CreatedTimestampTrait
{

    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime_immutable")
     */
    #[\Doctrine\ORM\Mapping\Column(type: 'datetime_immutable')]
    private ?\DateTimeInterface $createdAt = null;

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @Doctrine\ORM\Mapping\PrePersist()
     */
    #[\Doctrine\ORM\Mapping\PrePersist()]
    public function setCreatedTimestamp(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }
}