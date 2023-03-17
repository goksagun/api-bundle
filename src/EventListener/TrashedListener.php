<?php

declare(strict_types=1);

namespace Goksagun\ApiBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Goksagun\ApiBundle\Doctrine\Filter\TrashedFilter;
use Goksagun\ApiBundle\Entity\Util\DeletedTimestampInterface;

class TrashedListener
{
    public function preFlush(PreFlushEventArgs $args): void
    {
        $entityManager = $args->getObjectManager();
        $unitOfWork = $entityManager->getUnitOfWork();

        /** @var DeletedTimestampInterface $deletion */
        foreach ($unitOfWork->getScheduledEntityDeletions() as $deletion) {
            if (!$this->supports($entityManager, $deletion)) {
                continue;
            }

            $property = TrashedFilter::PROPERTY;
            $newValue = new \DateTime();
            $oldValue = $deletion->getDeletedAt();

            if ($oldValue instanceof \DateTimeInterface && $oldValue <= $newValue) {
                continue;
            }

            $deletion->setDeletedAt($newValue);

            $entityManager->persist($deletion);

            $unitOfWork->propertyChanged($deletion, $property, $oldValue, $newValue);
            $unitOfWork->scheduleExtraUpdate(
                $deletion,
                [
                    $property => [$oldValue, $newValue],
                ]
            );
        }
    }

    private function supports(EntityManagerInterface $entityManager, $entity): bool
    {
        return $entityManager->getClassMetadata(get_class($entity))
                ->reflClass->implementsInterface(DeletedTimestampInterface::class)
            && $entityManager->getFilters()->isEnabled(TrashedFilter::NAME);
    }
}