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
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    public function exists($id): bool
    {
        return (bool) $this->createQueryBuilder('entity')
            ->select('count(entity)')
            ->andWhere('entity.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
