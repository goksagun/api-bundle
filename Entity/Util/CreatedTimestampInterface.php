<?php

namespace Goksagun\ApiBundle\Entity\Util;

interface CreatedTimestampInterface
{
    public function getCreatedAt(): ?\DateTimeInterface;

    public function setCreatedAt(\DateTimeInterface $createdAt): CreatedTimestampInterface;
}