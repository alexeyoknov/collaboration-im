<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends NestedTreeRepository
{
    /**
     * @return Category[] Returns an array of CategoryNested objects
     */
    public function findAll(): array
    {
        return $this->findBy([],['root'=>'ASC','lft'=>'ASC']);

    }
    
    public function getAllSubCategories(int $id)
    {
        $em = $this->getEntityManager();
        $sql = 
        "SELECT  id
        FROM    (SELECT id, parent_id FROM category
                ORDER BY parent_id, id) category,
                (SELECT @pv := " . $id ." ) INITIALISATION
        WHERE   find_in_set(parent_id, @pv) > 0
            AND     @pv := concat(@pv, ',', id)";

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('App:Category', 'c');
        $rsm->addFieldResult('c','id','id');

        return $em->createNativeQuery($sql,$rsm)->getArrayResult();

    }

    public function getFirstCategories($parent, int $limit = 0)
    {
        $query = $this->createQueryBuilder('c');
        if (is_null($parent))
            $query->andWhere("c.Parent is NULL");
        else
            $query->andWhere("c.Parent = :value")
                ->setParameter('value', $parent);
            
        $query->orderBy('c.id', 'ASC');

        if ($limit > 0)
            $query->setMaxResults($limit);

        return $query->getQuery()->getResult();
    }

    public function getFirstCategoriesSql($parent, int $limit = 0)
    {
        $query = $this->createQueryBuilder('c')
            ->andWhere("c.Parent " . (is_null($parent)?"is null":"=" . $parent))
            //->setParameter('parent', $parent)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
        ;
        if ($limit > 0)
            $query->setMaxResults($limit);

        return $query->getSQL();
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
