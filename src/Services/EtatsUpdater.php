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

        $interval = date_diff($debutSortie, $now);
        $calcul = $interval->format('%i');


        $duree = (integer)$sortie->getDuree();
        $dureeSortie = new DateInterval('PT' . ((integer)$duree . 'M'));
        $finSortie = $debutSortie->add($dureeSortie);

        $interval2 = date_diff($finSortie, $now);
        $calcul2 = $interval2->format('%i');

        $finSortiePlusUnMois = $finSortie->add(new DateInterval('P1M'));

        $sorties = $this->sortieRepository->findAll();

        // Variables représentant les différentes valeurs d'un état
        $sortieOuverte = $this->etatRepository->find(['id' => 2]);
        $sortieCloturee = $this->etatRepository->find(['id' => 3]);
        $sortieEnCours = $this->etatRepository->find(['id' => 4]);
        $sortiePassee = $this->etatRepository->find(['id' => 5]);
        $sortieAnnulee = $this->etatRepository->find(['id' => 6]);

        foreach ($sorties as $sortie) {
            if ($sortie->getEtats() === $sortieOuverte) {
                if (($sortie->getParticipants()->count() == $sortie->getNombreInscriptionsMax()) || ($sortie->getDateLimiteInscription() < $now)) {
                    $sortie->setEtats($sortieCloturee);
                    $this->entityManager->persist($sortie);
                }
            }

            if ($sortie->getEtats() === $sortieCloturee) {
                if (($sortie->getParticipants()->count() < $sortie->getNombreInscriptionsMax()) && ($sortie->getDateLimiteInscription() > $now)) {
                    $sortie->setEtats($sortieOuverte);
                    $this->entityManager->persist($sortie);
                }
                if ($finSortie > $now && $debutSortie < $now) {
                    $sortie->setEtats($sortieEnCours);
                    $this->entityManager->persist($sortie);
                }
            }

            if ($sortie->getEtats() === $sortieEnCours) {
                if ($finSortie <= $now) {
                    $sortie->setEtats($sortiePassee);
                }
            }

            // Test pour passer la sortie à "Historisée"
            if ($sortie->getEtats() === $sortiePassee) {
                if ($finSortie <= $finSortiePlusUnMois) {
                    $estHistorisee = true;
            }

//            switch ($sortie->getEtats()) {
//                case $sortieOuverte:
//                    if (($sortie->getParticipants()->count() == $sortie->getNombreInscriptionsMax()) || ($sortie->getDateLimiteInscription() < $now)) {
//                        $sortie->setEtats($sortieCloturee);
//                        $this->entityManager->persist($sortie);
//                    }
//                    break;
//                case $sortieCloturee:
//                    if (($sortie->getParticipants()->count() < $sortie->getNombreInscriptionsMax()) && ($sortie->getDateLimiteInscription() > $now)) {
//                        $sortie->setEtats($sortieOuverte);
//                        $this->entityManager->persist($sortie);
//                    }
//                    if ($finSortie > $now && $calcul <= 0) {
//                        $sortie->setEtats($sortieEnCours);
//                        $this->entityManager->persist($sortie);
//                    }
//                    break;
//                case $sortieEnCours:
//                    if ($calcul2 >= 0) {
//                        $sortie->setEtats($sortiePassee);
//                        $this->entityManager->persist($sortie);
//                    }
//
//                /*// Si sortie de + d'1 mois => HISTORISEE (TODO : Masquer la sortie !)
//                else if ($debutSortie >= $oneMonth) {
//                    $estHistorisee = true;
//                }*/
//            }
            // Enregistrement infos dans la BDD
            $this->entityManager->flush();
        }
    }
}



