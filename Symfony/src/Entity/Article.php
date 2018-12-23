<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"article"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"article"})
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     * @Groups({"article"})
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"article"})
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="article")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"article"})
     */
    private $utilisateur;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Utilisateur", mappedBy="thumb")
     * @Groups({"article"})
     */
    private $thumb;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Sondage", mappedBy="article", cascade={"persist", "remove"})
     * @Groups({"article"})
     */
    private $sondage;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"article"})
     */
    private $paths = [];

    public function __construct()
    {
        $this->thumb = new ArrayCollection();
    }

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getTitre() : ? string
    {
        return $this->titre;
    }

    public function setTitre(? string $titre) : self
    {
        $this->titre = $titre;

        return $this;
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

    public function getDate() : ? \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date) : self
    {
        $this->date = $date;

        return $this;
    }

    public function getUtilisateur() : ? Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(? Utilisateur $utilisateur) : self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getThumb() : Collection
    {
        return $this->thumb;
    }

    public function addThumb(Utilisateur $thumb) : self
    {
        if (!$this->thumb->contains($thumb)) {
            $this->thumb[] = $thumb;
            $thumb->setThumb($this);
        }

        return $this;
    }

    public function removeThumb(Utilisateur $thumb) : self
    {
        if ($this->thumb->contains($thumb)) {
            $this->thumb->removeElement($thumb);
            // set the owning side to null (unless already changed)
            if ($thumb->getThumb() === $this) {
                $thumb->setThumb(null);
            }
        }

        return $this;
    }

    public function getSondage() : ? Sondage
    {
        return $this->sondage;
    }

    public function setSondage(? Sondage $sondage) : self
    {
        $this->sondage = $sondage;

        // set (or unset) the owning side of the relation if necessary
        $newArticle = $sondage === null ? null : $this;
        if ($newArticle !== $sondage->getArticle()) {
            $sondage->setArticle($newArticle);
        }

        return $this;
    }

    public function getPaths(): ?array
    {
        return $this->paths;
    }

    public function setPaths(?array $paths): self
    {
        $this->paths = $paths;

        return $this;
    }
}
