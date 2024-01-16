<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wish>
 *
 * @method Wish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wish[]    findAll()
 * @method Wish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wish::class);
    }

    /**
     * QueryBuilder
     */
    public function search(string $keyword,array $conditions): array{
        $qb = $this->createQueryBuilder("t");

        $flag = false;
        foreach( $conditions as $key => $val ){
            if($val){
                $qb->orWhere("t.$key LIKE :keyword");
                $flag=true;
            }
        }

        if($flag){
            $qb->setParameter('keyword','%'.$keyword.'%')
                ->addOrderBy('t.dateCreated', 'DESC');

        }
        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function pagination(int $page): array
    {
        $size = 10;
        $first = ($page - 1) * $size;
        $qb = $this->createQueryBuilder('t')
            ->setFirstResult($first)
            ->setMaxResults($size)
            ->addOrderBy('t.dateCreated', 'DESC')
            ->where('t.isPublished = 1');

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Wish[] Returns an array of Wish objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Wish
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
