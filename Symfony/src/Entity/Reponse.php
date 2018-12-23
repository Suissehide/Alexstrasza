<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReponseRepository")
 */
class Reponse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"article"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"article"})
     */
    private $contenu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sondage", inversedBy="reponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sondage;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Utilisateur", inversedBy="vote")
     * @Groups({"article"})
     */
    private $vote;

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getContenu() : ? string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu) : self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getSondage() : ? Sondage
    {
        return $this->sondage;
    }

    public function setSondage(? Sondage $sondage) : self
    {
        $this->sondage = $sondage;

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getVote() : Collection
    {
        return $this->vote;
    }

    public function addVote(Utilisateur $vote) : self
    {
        if (!$this->vote->contains($vote)) {
            $this->vote[] = $vote;
        }

        return $this;
    }

    public function removeVote(Utilisateur $vote) : self
    {
        if ($this->vote->contains($vote)) {
            $this->vote->removeElement($vote);
            // set the owning side to null (unless already changed)
            if ($vote->getVote() === $this) {
                $vote->setVote(null);
            }
        }

        return $this;
    }
}
