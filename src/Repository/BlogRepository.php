<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
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
            ->setParameter('val', "%".$value."%")
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

    # SELECT u.username, COUNT(b.id) FROM blog AS b INNER JOIN user AS u ON u.id = b.author_id GROUP BY u.id

    public function getBlogCountByUser () {
        return $this->createQueryBuilder('b')
            ->select("a.username AS x, COUNT(b.id) AS y")
            ->innerJoin("b.author", "a")
            ->groupBy("a.id")
            ->getQuery()
            ->getResult()
            ;
    }
}
