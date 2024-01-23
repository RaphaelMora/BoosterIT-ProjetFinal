<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Users>
 * @implements PasswordUpgraderInterface<Users>
 *
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }
    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        // Vérifie si l'utilisateur est une instance de la classe Users
        if (!$user instanceof Users) {
            // Lance une exception si l'utilisateur n'est pas pris en charge
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }
        // Met à jour le mot de passe de l'utilisateur avec le nouveau mot de passe hashé
        $user->setPassword($newHashedPassword);
        // Persiste les changements en base de données
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
    public function countVerifiedUsers(): int
    {
        // Crée un QueryBuilder pour construire la requête SQL. 'u' est un alias pour l'entité Users.
        return $this->createQueryBuilder('u')
            // Ajoute une condition 'where' pour filtrer les utilisateurs vérifiés.
            ->andWhere('u.isVerified = :val')
            // Définit la valeur du paramètre ':val' à 'true', correspondant aux utilisateurs vérifiés.
            ->setParameter('val', true)
            // Sélectionne le nombre d'identifiants d'utilisateurs (compte les utilisateurs).
            ->select('count(u.id)')
            // Prépare et obtient la requête SQL à partir du QueryBuilder.
            ->getQuery()
            // Exécute la requête et retourne un seul résultat sous forme de nombre.
            ->getSingleScalarResult();
    }
    //    /**
    //     * @return Users[] Returns an array of Users objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Users
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
