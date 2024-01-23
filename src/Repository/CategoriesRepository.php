<?php

namespace App\Repository;

use App\Entity\Categories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categories>
 *
 * @method Categories|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categories|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categories[]    findAll()
 * @method Categories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categories::class);
    }
    // Cette fonction recherche la valeur maximale de la colonne categoryOrder dans la table associée à ce repository.
    public function findMaxCategoryOrder()
    {
        // On commence la construction d'une requête Doctrine en utilisant le nom d'alias 'c' pour la table Categories.
        return $this->createQueryBuilder('c')
            // On sélectionne la valeur maximale de la colonne categoryOrder en utilisant la fonction d'agrégation MAX.
            ->select('MAX(c.categoryOrder)')
            // On finalise la construction de la requête et l'obtention de l'objet Query.
            ->getQuery()
            // On exécute la requête et on récupère le résultat sous forme de résultat scalaire unique.
            ->getSingleScalarResult();
    }
    //        /**
    //         * @return Categories[] Returns an array of Categories objects
    //         */
    //        public function findProductByCategory($value): array
    //        {
    //            return $this->createQueryBuilder('c')
    //
    //            ;
    //        }

    //    public function findOneBySomeField($value): ?Categories
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
