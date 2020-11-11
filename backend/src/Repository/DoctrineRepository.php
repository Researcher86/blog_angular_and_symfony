<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class DoctrineRepository extends ServiceEntityRepository
{
    public function save(object $entity): object
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    public function delete(int $id): void
    {
        $entity = $this->find($id);
        $this->em->remove($entity);
        $this->em->flush();
    }
}
