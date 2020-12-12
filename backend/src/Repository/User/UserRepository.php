<?php

declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User\User;
use App\Repository\DoctrineRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method array findAll()
 * @method array findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends DoctrineRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
}
