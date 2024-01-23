<?php

namespace App\Repository;

use App\Entity\Products;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Products>
 *
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    private $paginator;
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        // Constructeur de la classe, initialise le repository parent et le paginator.
        parent::__construct($registry, Products::class);
        $this->paginator = $paginator;
    }
    public function findProductsPaginated(int $page, string $slug, int $limit = 3): array
    {
        // Assure que $limit soit toujours positif
        $limit = abs($limit);
        // Initialise un tableau vide pour stocker les résultats
        $resultat = [];
        // Crée une requête QueryBuilder pour sélectionner des produits et catégories
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('c', 'p')
            ->from('App\Entity\Products', 'p')
            ->join('p.categories', 'c')
            ->where('c.slug = :slug') // Filtre par slug de catégorie
            ->setParameter('slug', $slug)
            ->setMaxResults($limit) // Limite le nombre de résultats par page
            ->setFirstResult(($page * $limit) - $limit); // Calcule la première entrée à récupérer en fonction de la page actuelle
        // Utilise le Paginator pour obtenir les résultats paginés
        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();
        // Si aucun résultat n'est trouvé, retourne le tableau vide
        if (empty($data)) {
            return $resultat;
        }
        // Calcule le nombre total de pages en fonction du nombre total de résultats et de la limite par page
        $pages = ceil($paginator->count() / $limit);
        // Stocke les données paginées et les informations de pagination dans le tableau résultat
        $resultat['data'] = $data;
        $resultat['pages'] = $pages;
        $resultat['page'] = $page;
        $resultat['limit'] = $limit;
        // Retourne le tableau résultat
        return $resultat;
    }
    public function findBySearchTerm(SearchData $searchData): PaginationInterface
    {
        // Crée un QueryBuilder pour l'entité Products (alias 'p')
        $queryBuilder = $this->createQueryBuilder('p')
            // Ajoute une condition WHERE pour rechercher des produits dont le nom ressemble à la valeur de :term
            ->where('p.name LIKE :term')
            // Lie le paramètre :term à la valeur '%' + texte de recherche + '%'
            ->setParameter('term', '%' . $searchData->q . '%')
            // Trie les résultats par ordre alphabétique ascendant (par nom)
            ->addOrderBy('p.name', 'asc');
        // Crée la requête SQL à partir du QueryBuilder
        $query = $queryBuilder->getQuery();
        // Utilise le service Paginator pour paginer les résultats de la requête
        // et retourne une interface de pagination (PaginationInterface)
        return $this->paginator->paginate($query, $searchData->page, 9);
    }
    public function findAllProductNames()
    {
        // Crée un QueryBuilder pour l'entité Products (alias 'p')
        return $this->createQueryBuilder('p')
            // Sélectionne uniquement le champ 'name' de l'entité Products
            ->select('p.name')
            // Indique que les résultats doivent être distincts (élimine les doublons)
            ->distinct(true)
            // Crée la requête SQL à partir du QueryBuilder
            ->getQuery()
            // Exécute la requête SQL et récupère les résultats
            ->getResult();
    }
    public function findByProductNames(array $selectedProductNames): array
    {
        // Crée un QueryBuilder pour l'entité Products (alias 'p')
        $qb = $this->createQueryBuilder('p');
        // Construit la condition WHERE : Utilise l'expression 'IN' pour filtrer par 'p.name' avec les noms passés en paramètre
        $qb->where($qb->expr()->in('p.name', ':names'))
            ->setParameter('names', $selectedProductNames);
        // Exécute la requête SQL et retourne les résultats
        return $qb->getQuery()->getResult();
    }
    //    /**
    //     * @return Products[] Returns an array of Products objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Products
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
