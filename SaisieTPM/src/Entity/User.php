<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Doc Api pour l'entity User : https://api-platform.com/docs/core/user/
 */

/**
 * Sécurisation des ressources par Roles
 */
#[ApiResource]
#[Get]
#[Put(security: "is_granted('ROLE_ADMIN') or object.owner == user")]
#[GetCollection]
#[Post(security: "is_granted('ROLE_ADMIN')")]

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'relation', targetEntity: Formulaire::class, orphanRemoval: true)]
    private Collection $formulaires;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: RenvoieSaisie::class)]
    private Collection $renvoieSaisies;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Champs::class, orphanRemoval: true)]
    private Collection $champs;

    public function __construct()
    {
        $this->formulaires = new ArrayCollection();
        $this->renvoieSaisies = new ArrayCollection();
        $this->champs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Formulaire>
     */
    public function getFormulaires(): Collection
    {
        return $this->formulaires;
    }

    public function addFormulaire(Formulaire $formulaire): self
    {
        if (!$this->formulaires->contains($formulaire)) {
            $this->formulaires->add($formulaire);
            $formulaire->setRelation($this);
        }

        return $this;
    }

    public function removeFormulaire(Formulaire $formulaire): self
    {
        if ($this->formulaires->removeElement($formulaire)) {
            // set the owning side to null (unless already changed)
            if ($formulaire->getRelation() === $this) {
                $formulaire->setRelation(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->username;
    }

    /**
     * @return Collection<int, RenvoieSaisie>
     */
    public function getRenvoieSaisies(): Collection
    {
        return $this->renvoieSaisies;
    }

    public function addRenvoieSaisy(RenvoieSaisie $renvoieSaisy): self
    {
        if (!$this->renvoieSaisies->contains($renvoieSaisy)) {
            $this->renvoieSaisies->add($renvoieSaisy);
            $renvoieSaisy->setUserId($this);
        }

        return $this;
    }

    public function removeRenvoieSaisy(RenvoieSaisie $renvoieSaisy): self
    {
        if ($this->renvoieSaisies->removeElement($renvoieSaisy)) {
            // set the owning side to null (unless already changed)
            if ($renvoieSaisy->getUserId() === $this) {
                $renvoieSaisy->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Champs>
     */
    public function getChamps(): Collection
    {
        return $this->champs;
    }

    public function addChamp(Champs $champ): self
    {
        if (!$this->champs->contains($champ)) {
            $this->champs->add($champ);
            $champ->setUtilisateur($this);
        }

        return $this;
    }

    public function removeChamp(Champs $champ): self
    {
        if ($this->champs->removeElement($champ)) {
            // set the owning side to null (unless already changed)
            if ($champ->getUtilisateur() === $this) {
                $champ->setUtilisateur(null);
            }
        }

        return $this;
    }
}