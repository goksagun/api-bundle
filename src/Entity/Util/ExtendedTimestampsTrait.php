<?php

namespace Goksagun\ApiBundle\Entity\Util;

use Doctrine\ORM\Mapping\MappedSuperclass;

#[MappedSuperclass]
trait ExtendedTimestampsTrait
{
    use CreatedTimestampTrait;
    use UpdatedTimestampTrait;
    use DeletedTimestampTrait;
}