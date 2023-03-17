<?php

namespace Goksagun\ApiBundle\Entity\Util;

use Doctrine\ORM\Mapping\MappedSuperclass;

#[MappedSuperclass]
trait DeletedTimestampTrait
{

    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime_immutable", nullable=true)
     */
    #[\Doctrine\ORM\Mapping\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeInterface $deletedAt = null;

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt instanceof \DateTimeInterface && $this->deletedAt <= new \DateTimeImmutable();
    }
}