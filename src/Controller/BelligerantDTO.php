<?php
/** src/Controller/ResultatController.php */ 
namespace App\Controller;

use App\Entity\Guilde;
use App\Entity\Joueur;
use Doctrine\Common\Collections\ArrayCollection;

class BelligerantDTO
{

    private ?Joueur $joueur;
    private ?Guilde $guilde;
    private ?ArrayCollection $personnages;


    /**
     * Get the value of joueur
     */
    public function getJoueur(): ?Joueur
    {
        return $this->joueur;
    }

    /**
     * Set the value of joueur
     */
    public function setJoueur(?Joueur $joueur): self
    {
        $this->joueur = $joueur;

        return $this;
    }

    /**
     * Get the value of guilde
     */
    public function getGuilde(): ?Guilde
    {
        return $this->guilde;
    }

    /**
     * Set the value of guilde
     */
    public function setGuilde(?Guilde $guilde): self
    {
        $this->guilde = $guilde;

        return $this;
    }

    /**
     * Get the value of personnages
     */
    public function getPersonnages(): ?ArrayCollection
    {
        return $this->personnages;
    }

    /**
     * Set the value of personnages
     */
    public function setPersonnages(?ArrayCollection $personnages): self
    {
        $this->personnages = $personnages;

        return $this;
    }
}