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
        $debutSortie = $sortie->getDateHeureDebut();

        $oneDayAdded = new DateTime('now');
        $intervalD = new DateInterval('P1D');
        $oneDayAdded->add($intervalD);

        $oneMonth = new DateTime('now');
        $intervalM = new DateInterval('P1M');
        $oneMonth->add($intervalM);

        $sorties = $this->sortieRepository->findAll();

        // Variables représentant les différentes valeurs d'un état
        $sortieOuverte = $this->etatRepository->find(['id' => 2]);
        $sortieCloturee = $this->etatRepository->find(['id' => 3]);
        $sortieEnCours = $this->etatRepository->find(['id' => 4]);
        $sortiePassee = $this->etatRepository->find(['id' => 5]);
        $sortieAnnulee = $this->etatRepository->find(['id' => 6]);

        foreach ($sorties as $sortie) {
            switch ($sortie->getEtats()) {
                case $sortieOuverte:
                    if (($sortie->getParticipants()->count() >= $sortie->getNombreInscriptionsMax()) || ($sortie->getDateLimiteInscription() < $now))
                        $sortie->setEtats($sortieCloturee);
                    if ($debutSortie = $now)
                        $sortie->setEtats($sortieEnCours);
                    break;
                case $sortieCloturee:
                    if (($sortie->getParticipants()->count() < $sortie->getNombreInscriptionsMax()) && ($sortie->getDateLimiteInscription() > $now))
                        $sortie->setEtats($sortieOuverte);
                    if ($debutSortie < $oneDayAdded)
                        $sortie->setEtats($sortiePassee);
                    break;


                    /*// Si sortie de + d'1 mois => HISTORISEE (TODO : Masquer la sortie !)
                    else if ($debutSortie >= $oneMonth) {
                        $estHistorisee = true;
                    }*/
            }
            
            $this->entityManager->flush();
        }
    }
}



