<?php

namespace App\Entity;

use App\Repository\UploadRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UploadRepository::class)
 */
class Upload
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="blob")
     */
    private $Name;



    /**
     * @ORM\OneToOne(targetEntity=Participant::class, mappedBy="uploadId", cascade={"persist", "remove"})
     */
    private $participant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->Name;
    }

    public function setName($Name)
    {
        $this->Name = $Name;

        return $this;
    }



    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setParticipant(Participant $participant): self
    {
        // set the owning side of the relation if necessary
        if ($participant->getUploadId() !== $this) {
            $participant->setUploadId($this);
        }

        $this->participant = $participant;

        return $this;
    }
}
