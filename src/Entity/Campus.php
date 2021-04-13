<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $nomCampus;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCampus(): ?string
    {
        return $this->nomCampus;
    }

    public function setNomCampus(string $nomCampus): self
    {
        $this->nomCampus = $nomCampus;

        return $this;
    }
}
