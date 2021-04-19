<?php

namespace App\Services;

use App\Entity\Sortie;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use DateTime;

class EtatsUpdater
{
    protected $sortieRepository;
    protected $etatRepository;

    public function __construct(SortieRepository $sortieRepository, EtatRepository $etatRepository)
    {
        $this->sortieRepository = $sortieRepository;
        $this->etatRepository = $etatRepository;
    }


    public function miseAJourEtatSortie(Sortie $sortie)
    {
        $now = new DateTime();

        $sorties = $this->sortieRepository->findAll();

        // Variables représentant les différentes valeurs d'un état
        $sortieCreee = $this->etatRepository->find(['id' => 1]);
        $sortieOuverte = $this->etatRepository->find(['id' => 2]);
        $sortieCloturee = $this->etatRepository->find(['id' => 3]);
        $sortieEnCours = $this->etatRepository->find(['id' => 4]);
        $sortiePassee = $this->etatRepository->find(['id' => 5]);
        $sortieAnnulee = $this->etatRepository->find(['id' => 6]);
        // TODO A VOIR SI VARIABLE $sortieHistorisee; EST NECESSAIRE


        foreach ($sorties as $sortie)
            // Sortie créée = sortie ouverte
            if ($sortie->getEtats() === $sortieCreee) {
                $sortie->setEtats($sortieOuverte);
            }
        // Si la sortie est ouverte && le nb de participant = nb inscriptions max  => Cloturée
//        if ($sortie->getEtats() === $sortieOuverte) {
//            if ($sortie->getParticipants() = $sortie->getNombreInscriptionsMax()) {
 //               $sortie->setEtats($sortieCloturee);
 //           }
//            else {
//                $sortie->setEtats($sortieOuverte);
//            }
//        }

        // Si la date du début de sortie est inf. à aujourd'hui => etat = passée
        if ($sortie->getDateHeureDebut() < $now) {
            $sortie->setEtats($sortiePassee);
        }

        // Si

    }


}
