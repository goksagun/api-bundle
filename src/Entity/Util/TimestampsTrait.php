<?php

namespace Goksagun\ApiBundle\Entity\Util;

use Doctrine\ORM\Mapping\MappedSuperclass;

#[MappedSuperclass]
trait TimestampsTrait
{
    use CreatedTimestampTrait;
    use UpdatedTimestampTrait;
}