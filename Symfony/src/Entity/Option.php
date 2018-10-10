<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OptionRepository")
 */
class Option
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contenu;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Utilisateur", inversedBy="vote")
     */
    private $vote;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sondage", inversedBy="options")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sondage;

    public function __construct()
    {
        $this->vote = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getVote(): Collection
    {
        return $this->vote;
    }

    public function addVote(Utilisateur $vote): self
    {
        if (!$this->vote->contains($vote)) {
            $this->vote[] = $vote;
        }

        return $this;
    }

    public function getSondage(): ?Sondage
    {
        return $this->sondage;
    }

    public function setSondage(?Sondage $sondage): self
    {
        $this->sondage = $sondage;

        return $this;
    }
}
