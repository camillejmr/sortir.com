<?php

namespace App/Entity/Inscription;

use Doctrine\ORM\Mapping as ORM;

/**
 * AncienneInscription
 *
 * @ORM\Table(name="ancienne_inscription")
 * @ORM\Entity
 */
class AncienneInscription
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_inscription", type="datetime", nullable=false)
     */
    private $dateInscription;


}
