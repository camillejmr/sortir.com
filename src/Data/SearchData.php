<?php


namespace App\Data;


use App\Entity\Campus;
use DateTime;

// Objet qui représente nos données
class SearchData
{
    /**
     * @var Campus[]
     */
    public $campus = [];

    /**
     * @var string
     */
    public $champRecherche = '';

    /**
     * @var DateTime
     */
    public $dateMin;

    /**
     * @var DateTime
     */
    public $dateMax;

    /**
     * @var boolean
     */
    public $estOrganisateur = false;  // Ne coche pas la case par défaut

    /**
     * @var boolean
     */
    public $estInscrit = false;

    /**
     * @var boolean
     */
    public $estNonInscrit = false;

    /**
     * @var boolean
     */
    public $sortieTerminee = false;

}
