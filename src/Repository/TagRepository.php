<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function getBlogCountByTag() {

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

        return $this->createQueryBuilder("t")
            ->select("t.name, count(t.id) AS count")
            ->innerJoin("t.blogs", "b")
            ->groupBy("t.id")
            ->getQuery()
            ->getResult();
    }
}
