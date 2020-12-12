<?php

declare(strict_types=1);

namespace App\Repository\Article;

use App\Entity\Article\Article;
use App\Repository\DoctrineRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method list<Article> findAll()
 * @method list<Article> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends DoctrineRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

//    public function getCommentById(int $commentId): Comment
//    {
//        $comments = $this->createQueryBuilder('a')
//            ->select('comments')
//            ->andWhere(':commentId MEMBER OF a.comments')
//            ->innerJoin('a.comments', 'comments')
//            ->andWhere(':commentId = comments.id')
//            ->setParameter('commentId', $commentId)
//            ->getQuery()
//            ->getResult();
//        return $comments[0];
//
//        $comments = $this->_em->createQuery(
//          'select a.comments from App\Entity\Article\Article a where :commentId MEMBER OF a.comments'
//        )
//        ->setParameter('commentId', $commentId)
//        ->getResult();
//
//        return $comments[0];
//    }
}
