<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    /**
     * @param $value
     * @return [] Blog Returns an array of Blog objects
     */
    public function findByTitleOrContentField($value)
    {
        return $this->createQueryBuilder('b')
            ->where("b.title LIKE :val")
            ->orWhere("b.content LIKE :val")
            ->andWhere("b.status = 1")
            ->setParameter('val', "%$value%")
            ->orderBy("b.created_at", "desc")
            ->getQuery()
            ->getResult();
    }

    public function findSelfBlogs($user) {
        return $this->createQueryBuilder('b')
            ->where('b.author = :user')
            ->setParameter('user', $user)
            ->orderBy("b.created_at","desc");
    }

    /*
    public function findOneBySomeField($value): ?Blog
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
