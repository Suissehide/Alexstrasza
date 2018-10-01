<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 * @UniqueEntity(fields="username", message="Ce nom d'utilisateur est déjà utilisé.")
 */
class Utilisateur implements UserInterface, EquatableInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8, max=4096)
     * @Assert\Regex(pattern="/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/", message="Cette chaîne doit contenir au moins une majuscule, une minuscule et un nombre.")
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="utilisateur", orphanRemoval=true)
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="thumb")
     */
    private $thumb;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Option", mappedBy="vote")
     */
    private $vote;

    private $salt;

    public function __construct()
    {
        $this->article = new ArrayCollection();
        $this->vote = new ArrayCollection();
    }

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getUsername() : ? string
    {
        return $this->username;
    }

    public function setUsername(string $username) : self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword() : ? string
    {
        return $this->password;
    }

    public function setPassword(string $password) : self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getRoles() : ? array
    {
        return $this->roles;
    }

    public function setRoles(array $roles) : self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticle() : Collection
    {
        return $this->article;
    }

    public function addArticle(Article $article) : self
    {
        if (!$this->article->contains($article)) {
            $this->article[] = $article;
            $article->setUtilisateur($this);
        }

        return $this;
    }

    public function removeArticle(Article $article) : self
    {
        if ($this->article->contains($article)) {
            $this->article->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUtilisateur() === $this) {
                $article->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function getThumb() : ? Article
    {
        return $this->thumb;
    }

    public function setThumb(? Article $thumb) : self
    {
        $this->thumb = $thumb;

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getVote() : Collection
    {
        return $this->vote;
    }

    public function addVote(Option $vote) : self
    {
        if (!$this->vote->contains($vote)) {
            $this->vote[] = $vote;
            $vote->addVote($this);
        }

        return $this;
    }

    public function removeVote(Option $vote) : self
    {
        if ($this->vote->contains($vote)) {
            $this->vote->removeElement($vote);
            $vote->removeVote($this);
        }

        return $this;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof Utilisateur) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }
}
