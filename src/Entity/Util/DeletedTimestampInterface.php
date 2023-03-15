<?php

namespace Goksagun\ApiBundle\Entity\Util;

interface DeletedTimestampInterface
{
    public function getDeletedAt(): ?\DateTimeInterface;

    public function setDeletedAt(?\DateTimeInterface $deletedAt): static;
}