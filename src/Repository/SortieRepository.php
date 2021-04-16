<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Sortie;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /*
     * Récupère les sorties en lien avec une recherche
     * @return PaginationInterface
     */
    public function findSearch(SearchData $search)
    {
        $queryBuilder = $this->createQueryBuilder('s'); // On récupère les sorties qu'on renomme s

        // Selectionne toutes les infos liées aux sorties, mais aussi aux campus, aux dates, etc
        $queryBuilder->select('s', 'p', 'c', 'l', 'e')

            // On fait des liaisons avec les choses qu'on recherche pour ne faire qu'une requete
            ->leftJoin('s.participants', 'p')    // leftjoin pour retourner les sorties meme si elles n'ont pas de participants
            ->leftJoin('s.campus', 'c')
            ->leftJoin('s.lieux', 'l')
            ->leftJoin('s.etats', 'e');

        // Si le parametre 'champRecherche' est rempli, le nom de la sortie doit correspondre
        if (!empty($search->champRecherche)) {
            $query = $queryBuilder
                ->andWhere('s.nom LIKE :champRecherche')
                ->setParameter('champRecherche', "%{$search->champRecherche}%"); // Pour faire des recherches partielles
        }

        // Pour la date minimum
        if (!empty($search->dateMin)) {
            $query = $queryBuilder
                ->andWhere('s.dateHeureDebut >= :dateMin') // le résultat doit etre supérieur ou égal à la valeur dateMin
                ->setParameter('dateMin', $search->dateMin);
        }

        // Pour la date max
        if (!empty($search->dateMax)) {
            $query = $queryBuilder
                ->andWhere('s.dateHeureDebut <= :dateMax')
                ->setParameter('dateMax', $search->dateMax);
        }

        if (!empty($search->campus)) {
            $query = $queryBuilder
                ->andWhere('s.campus IN (:campus)')  // On veut que le campus existe dans une liste qu'on enverra
                ->setParameter('campus', $search->campus);
        }

        /*if (!empty($search->estInscrit)) {
            $query = $queryBuilder
                ->andWhere('s.participants' == true);

            }*/

        // TODO : FILTRES CHECKBOX
        /*
        if (!empty($search->estOrganisateur)) {
            $query = $query

        }

        if (!empty($search->estInscrit) && (app.user.id == participant.id) {
            $query = $query

        }

        if (!empty($search->estNonInscrit)) {
            $query = $query

        }

        $datetime = new DateTime();
        if (!empty($search->sortieTerminee)) {
            $query = $query
        } */


        $query = $queryBuilder->getQuery();

        $query->setMaxResults(15);

        $paginator = new Paginator($query);
        return $paginator;



    }
}
