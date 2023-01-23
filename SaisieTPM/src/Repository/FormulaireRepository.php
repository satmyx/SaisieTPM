<?php

namespace App\Repository;

use App\Entity\Formulaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formulaire>
 *
 * @method Formulaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formulaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formulaire[]    findAll()
 * @method Formulaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormulaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formulaire::class);
    }

    public function save(Formulaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Formulaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getCountFormulaireByUser(ManagerRegistry $doctrine, $id) {
        $manager = $doctrine->getManager();
        $sql = "SELECT count(*) as nbForm
        FROM formulaire
        WHERE formulaire.relation_id = :id";
        $statement = $manager->getConnection()->prepare($sql);
        $result = $statement->executeQuery(['id' => $id]);
        return $result->fetchAll();
    }

    public function deleteAllDataFormById(ManagerRegistry $doctrine, $id, $iduser) {
        $manager = $doctrine->getManager();
        $sql = "DELETE champs_formulaire, champs, formulaire
        FROM formulaire
        INNER JOIN champs_formulaire
        ON champs_formulaire.formulaire_id = formulaire.id
        INNER JOIN champs
        ON champs_formulaire.champs_id = champs.id
        WHERE formulaire.id = :id AND formulaire.relation_id = :iduser";
        $statement = $manager->getConnection()->prepare($sql);
        $statement->executeQuery(['id' => $id, 'iduser' => $iduser]);
    }
//    /**
//     * @return Formulaire[] Returns an array of Formulaire objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Formulaire
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
