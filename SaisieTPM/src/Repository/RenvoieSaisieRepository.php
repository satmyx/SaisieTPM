<?php

namespace App\Repository;

use App\Entity\RenvoieSaisie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RenvoieSaisie>
 *
 * @method RenvoieSaisie|null find($id, $lockMode = null, $lockVersion = null)
 * @method RenvoieSaisie|null findOneBy(array $criteria, array $orderBy = null)
 * @method RenvoieSaisie[]    findAll()
 * @method RenvoieSaisie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RenvoieSaisieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RenvoieSaisie::class);
    }

    public function save(RenvoieSaisie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RenvoieSaisie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RenvoieSaisie[] Returns an array of RenvoieSaisie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RenvoieSaisie
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
