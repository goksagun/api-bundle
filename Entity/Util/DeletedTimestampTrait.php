<?php

namespace Goksagun\ApiBundle\Entity\Util;

trait DeletedTimestampTrait
{

    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime", nullable=true)
     */
    #[\Doctrine\ORM\Mapping\Column(type: 'datetime', nullable: true)]
    private $deletedAt;

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): DeletedTimestampInterface
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt instanceof \DateTimeInterface && $this->deletedAt <= new \DateTime();
    }
}