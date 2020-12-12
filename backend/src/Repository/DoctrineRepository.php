<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\ORMException;
use ReflectionClass;
use RuntimeException;

abstract class DoctrineRepository extends ServiceEntityRepository
{
    public function save(object $entity): object
    {
        $this->_em->persist($entity);
        $this->_em->flush();

        return $entity;
    }

    public function delete(int $id): object
    {
        $entity = $this->getById($id);
        $this->_em->remove($entity);
        $this->_em->flush();

        return $entity;
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
        if (! $entity) {
            throw new EntityNotFoundException(\sprintf('%s not found by id [%d]', $this->getShortEntityName(), $id));
        }

        if (! is_object($entity)) {
            throw new ORMException(\sprintf('Return %s is not object [%d]', $this->getShortEntityName(), $id));
        }

        return $entity;
    }

    protected function getShortEntityName(): string
    {
        if (! \class_exists($this->_entityName)) {
            throw new RuntimeException(\sprintf('Class "%s" is not exists', $this->_entityName));
        }

        return (new ReflectionClass($this->_entityName))->getShortName();
    }
}
