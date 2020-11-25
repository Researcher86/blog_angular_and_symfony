<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use ReflectionClass;

abstract class DoctrineRepository extends ServiceEntityRepository
{
    public function save(object $entity): object
    {
        $this->_em->persist($entity);
        $this->_em->flush();
        return $entity;
    }

    public function delete(int $id): void
    {
        $entity = $this->find($id);
        $this->_em->remove($entity);
        $this->_em->flush();
    }

    public function exists(int $id): bool
    {
        return (bool) $this->createQueryBuilder('entity')
            ->select('count(entity)')
            ->andWhere('entity.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getById(int $id): object
    {
        $entity = $this->find($id);
        if (!$entity) {
            throw new EntityNotFoundException(\sprintf('%s not found by id [%d]', $this->getShortEntityName(), $id));
        }

        return $entity;
    }

    protected function getShortEntityName(): string
    {
        return (new ReflectionClass($this->_entityName))->getShortName();
    }
}
