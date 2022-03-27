<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Product $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Product $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findNewProducts(int $days, int $offset = 0, int $limit = 0)
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.created >= :begin')
            ->andWhere('p.created <= :end')
            ->setParameter('begin', new \DateTime("-" . $days . " days"))
            ->setParameter('end', new \DateTime('now'))
            ->getQuery();

        if ($limit>0) {
            $query->setMaxResults($limit);
                    
            if ($offset>0)
                $query->setFirstResult($offset * $limit);
        }

        return $query->getResult();
    }

    public function findRandProducts(int $limit)
    {
        $em = $this->getEntityManager();
        $sql = "SELECT * FROM product as p ORDER BY RAND() LIMIT " . $limit;
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('App:Product', 'p');
        $rsm->addFieldResult('p','id','id');
        $rsm->addFieldResult('p','name','name');
        $rsm->addFieldResult('p','description','description');
        $rsm->addFieldResult('p','created','created');
        $rsm->addFieldResult('p','updated','updated');
        $rsm->addFieldResult('p','price','price');
        $rsm->addFieldResult('p','active','active');

        return $em->createNativeQuery($sql,$rsm)->getResult();
    }

    public function findAllProductsInCategory(array $categories, int $offset = 0, int $limit = 0, string $orderBy = 'id', string $orderType = 'ASC')
    {
        if ( $orderBy === 'rating' ) {
            // сделать сложный запрос с джоином к комментам и сортировкой, а пока заглушка
            $orderBy = 'id';
        }

        $query = $this->createQueryBuilder('p')
            ->andWhere('p.Category IN (:ids)')
            ->orderBy("p." . $orderBy,$orderType)
            ->setParameter('ids', $categories)
            ->getQuery();
        
        $allProductsCount = count($query->getResult());
        if ($limit>0) {
            $query->setMaxResults($limit);
            
            if ($offset>0)
                $query->setFirstResult($offset * $limit);
        }

        return [
            'query' => $query->getResult(),
            'totalCount' => $allProductsCount
        ];
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findByName($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }
}
