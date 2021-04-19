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

    }

}
