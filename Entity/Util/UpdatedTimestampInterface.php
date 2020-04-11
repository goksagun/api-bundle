<?php

namespace Goksagun\ApiBundle\Entity\Util;

interface UpdatedTimestampInterface
{
    public function getUpdatedAt(): ?\DateTimeInterface;

    public function setUpdatedAt(\DateTimeInterface $updatedAt): UpdatedTimestampInterface;
}