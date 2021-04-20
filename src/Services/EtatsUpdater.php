<?php

namespace App\Services;

use App\Entity\Sortie;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use DateInterval;
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
        $now = new DateTime('now');
        $debutSortie = $sortie->getDateHeureDebut();
        $oneMonth = new DateTime('now');
        $interval = new DateInterval('P1M');
        $oneMonth->add($interval);

        $sorties = $this->sortieRepository->findAll();

        // Variables représentant les différentes valeurs d'un état
        $sortieCreee = $this->etatRepository->find(['id' => 1]);
        $sortieOuverte = $this->etatRepository->find(['id' => 2]);
        $sortieCloturee = $this->etatRepository->find(['id' => 3]);
        $sortieEnCours = $this->etatRepository->find(['id' => 4]);
        $sortiePassee = $this->etatRepository->find(['id' => 5]);
        $sortieAnnulee = $this->etatRepository->find(['id' => 6]);


        foreach ($sorties as $sortie) {
            // Si la sortie est ouverte && date cloture atteinte => Cloturée
            if (($sortie->getParticipants()->count() >= $sortie->getNombreInscriptionsMax()) || ($sortie->getDateLimiteInscription() < $now)) {
                $sortie->setEtats($sortieCloturee);
            } //Si le nb de participant = nb inscriptions max  => Cloturée : FONCTIONNE !!
            else if (($sortie->getParticipants()->count() < $sortie->getNombreInscriptionsMax()) && ($sortie->getDateLimiteInscription() > $now)) {
                $sortie->setEtats($sortieOuverte);
            } // Si l'activité se déroule maintenant et qu'il y a au minimum 1 participant => Activité en cours
            else if ($debutSortie <= $now && $sortie->getParticipants()->count() >= 1) {
                $sortie->setEtats($sortieEnCours);
            } // Si la date du début de sortie est inf. à aujourd'hui => etat = passée
            else if ($sortie->getEtats() === $sortieCloturee && $debutSortie > $now) {
                $sortie->setEtats($sortiePassee);
            } // Si sortie de + d'1 mois => HISTORISEE (TODO : Masquer la sortie !)
            else if ($debutSortie >= $oneMonth) {
                $estHistorisee = true;
            }

        }



    }

}



