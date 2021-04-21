<?php

namespace App\Services;

use App\Entity\Sortie;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class EtatsUpdater
{
    protected $sortieRepository;
    protected $etatRepository;
    protected $entityManager;

    public function __construct(SortieRepository $sortieRepository, EtatRepository $etatRepository, EntityManagerInterface $entityManager)
    {
        $this->sortieRepository = $sortieRepository;
        $this->etatRepository = $etatRepository;
        $this->entityManager = $entityManager;
    }


    public function miseAJourEtatSortie(Sortie $sortie)
    {
        $now = new DateTime('now');
        $now->format("D/M/Y H:i:s");
        $debutSortie = new DateTime($sortie->getDateHeureDebut());
        $duree = (integer)$sortie->getDuree();
        $dureeSortie = new DateInterval('PT' . ((integer)$duree . 'M'));
        $finSortie = $debutSortie->add($dureeSortie);
        $finSortiePlusUnMois = $finSortie->add(new DateInterval('P1M'));

//        $interval = date_diff($debutSortie, $now);
//        $calcul = $interval->format('%i');
//        $interval2 = date_diff($finSortie, $now);
//        $calcul2 = $interval2->format('%i');

        $sorties = $this->sortieRepository->findAll();

        // Variables représentant les différentes valeurs d'un état
        $sortieOuverte = $this->etatRepository->find(['id' => 2]);
        $sortieCloturee = $this->etatRepository->find(['id' => 3]);
        $sortieEnCours = $this->etatRepository->find(['id' => 4]);
        $sortiePassee = $this->etatRepository->find(['id' => 5]);
        $sortieAnnulee = $this->etatRepository->find(['id' => 6]);
        $sortieHistorisee = $this->etatRepository->find(['id' => 7]);

        foreach ($sorties as $sortie) {

            if ($sortie->getEtats() === $sortieOuverte) {
                // OK
                if (($sortie->getParticipants()->count() == $sortie->getNombreInscriptionsMax()) || ($sortie->getDateLimiteInscription() < $now)) {
                    $sortie->setEtats($sortieCloturee);
                }
            }

            if ($sortie->getEtats() === $sortieCloturee) {
                // OK
                if (($sortie->getParticipants()->count() < $sortie->getNombreInscriptionsMax()) && ($sortie->getDateLimiteInscription() > $now)) {
                    $sortie->setEtats($sortieOuverte);
                }
                // NE MARCHE PAS
                if ($now < $finSortie && $now >= $sortie->getDateHeureDebut()) {
                    $sortie->setEtats($sortieEnCours);
                }
            }

            // Ne marche pas !!
            if ($sortie->getEtats() === $sortieEnCours) {
                if ($finSortie < $now) {
                    $sortie->setEtats($sortiePassee);
                }
            }

            // Test pour passer la sortie à "Historisée"
            if ($sortie->getEtats() === $sortiePassee) {
                if ($finSortie <= $finSortiePlusUnMois) {
                    $sortie->setEtats($sortieHistorisee);
                }
            }

            // Enregistrement infos dans la BDD
            $this->entityManager->persist($sortie);
            $this->entityManager->flush();

        }
    }
}



