<?php

declare(strict_types=1);

namespace Goksagun\ApiBundle\Doctrine\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Goksagun\ApiBundle\Entity\Util\DeletedTimestampInterface;

class TrashedFilter extends SQLFilter
{
    public const NAME = 'trashed';
    public const FIELD = 'deleted_at';
    public const PROPERTY = 'deletedAt';

    /**
     * @inheritDoc
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (!$targetEntity->reflClass->implementsInterface(DeletedTimestampInterface::class)) {
            return '';
        }

        return sprintf(
            '%s.%s IS NULL || NOW() < %s.%s',
            $targetTableAlias,
            static::FIELD,
            $targetTableAlias,
            static::FIELD
        );
    }
}