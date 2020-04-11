<?php

namespace Goksagun\ApiBundle\Entity\Util;

trait UpdatedTimestampTrait
{

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): UpdatedTimestampInterface
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function setUpdatedTimestamp()
    {
        $this->updatedAt = new \DateTime();
    }
}