<?php

namespace App\Repository;

use App\Entity\ContentCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContentCart>
 *
 * @method ContentCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContentCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContentCart[]    findAll()
 * @method ContentCart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentCartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContentCart::class);
    }

    public function save(ContentCart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ContentCart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
        * @return ContentCart[] Returns an array of ContentCart objects
    */
    public function findProductInCart($productId, $cartId): array
    {
        return $this->createQueryBuilder('c')
        ->andWhere('c.cart = :cartId')
        ->andWhere('c.product = :productId')
        ->setParameter('cartId', $cartId)
        ->setParameter('productId', $productId)
        ->orderBy('c.id', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?ContentCart
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
