<?php

namespace App\Repository;

use App\Entity\Champs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Champs>
 *
 * @method Champs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Champs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Champs[]    findAll()
 * @method Champs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChampsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Champs::class);
    }

    public function save(Champs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Champs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /*
    [- Permet d'obtenir les informations concernant un formulaire -]
    */

    public function getInfoFormulaire (ManagerRegistry $doctrine, $id) {
        $manager = $doctrine->getManager();
        $sql = "SELECT formulaire.id, formulaire.nom as nomformulaire, formulaire.relation_id, type_champs.typage, champs.nom 
        FROM type_champs
        INNER JOIN champs
        ON champs.id_type_id = type_champs.id
        INNER JOIN champs_formulaire
        ON champs.id = champs_formulaire.champs_id
        INNER JOIN formulaire
        ON formulaire.id = champs_formulaire.formulaire_id
        WHERE formulaire.id  = :id";
        $statement = $manager->getConnection()->prepare($sql);
        $result = $statement->executeQuery(['id' => $id]);
        return $result->fetchAll();
    }

    public function getCountChampsByUser(ManagerRegistry $doctrine, $id) {
        $manager = $doctrine->getManager();
        $sql = "SELECT count(*) as nbChamps
        FROM champs
        WHERE champs.utilisateur_id = :id";
        $statement = $manager->getConnection()->prepare($sql);
        $result = $statement->executeQuery(['id' => $id]);
        return $result->fetchAll();
    }

    public function deleteChampsById(ManagerRegistry $doctrine, $id) {
        $manager = $doctrine->getManager();
        $sql = "DELETE champs
        FROM champs
        WHERE champs.id = :id";
        $statement = $manager->getConnection()->prepare($sql);
        $statement->executeQuery(['id' => $id]);
    }
//    /**
//     * @return Champs[] Returns an array of Champs objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Champs
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
