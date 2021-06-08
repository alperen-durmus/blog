<?php

namespace App\Repository;

use App\Entity\BlogCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlogCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogCategory[]    findAll()
 * @method BlogCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogCategory::class);
    }

    public function getBlogCountByCategory() {

//        $sql = "
//            SELECT
//                bc.name,
//                count(bc.id)
//            FROM
//                blog_category AS bc
//            INNER JOIN
//                blog_blog_category AS bbc ON
//                    bc.id = bbc.blog_category_id
//            GROUP BY
//                bbc.blog_category_id";

        return $this->createQueryBuilder("bc")
            ->select("bc.name, count(bc.id) AS count")
            ->innerJoin("bc.blogs", "b")
            ->groupBy("bc.id")
            ->getQuery()
            ->getResult();
    }
}
