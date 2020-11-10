<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

abstract class DoctrineRepository extends ServiceEntityRepository
{
    protected EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry, $entityClass, EntityManagerInterface $em)
    {
        parent::__construct($registry, $entityClass);
        $this->em = $em;
    }

    public function save(object $entity): object
    {
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }

    public function delete(int $id): void
    {
        $entity = $this->find($id);
        $this->em->remove($entity);
        $this->em->flush();
    }
}
