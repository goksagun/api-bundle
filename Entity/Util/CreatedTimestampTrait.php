<?php

namespace Goksagun\ApiBundle\Entity\Util;

trait CreatedTimestampTrait
{

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): CreatedTimestampInterface
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedTimestamp()
    {
        $this->createdAt = new \DateTime();

        if ($this instanceof UpdatedTimestampInterface) {
            $this->updatedAt = $this->createdAt;
        }
    }
}