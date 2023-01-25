<?php

namespace App\Entity;

use App\Repository\FormulaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;

#[ApiResource]
#[Get]
#[Put(security: "is_granted('ROLE_ADMIN') or object.owner == user")]
#[GetCollection]
#[Post(security: "is_granted('ROLE_USER')")]

#[ORM\Entity(repositoryClass: FormulaireRepository::class)]
class Formulaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'formulaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $relation = null;

    #[ORM\ManyToMany(targetEntity: Champs::class, mappedBy: 'choisir')]
    private Collection $champs;

    #[ORM\OneToMany(mappedBy: 'fomulaire_id', targetEntity: RenvoieSaisie::class)]
    private Collection $renvoieSaisies;

    public function __construct()
    {
        $this->champs = new ArrayCollection();
        $this->renvoieSaisies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getRelation(): ?User
    {
        return $this->relation;
    }

    public function setRelation(?User $relation): self
    {
        $this->relation = $relation;

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
            $champ->addChoisir($this);
        }

        return $this;
    }

    public function removeChamp(Champs $champ): self
    {
        if ($this->champs->removeElement($champ)) {
            $champ->removeChoisir($this);
        }

        return $this;
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
            $renvoieSaisy->setFomulaireId($this);
        }

        return $this;
    }

    public function removeRenvoieSaisy(RenvoieSaisie $renvoieSaisy): self
    {
        if ($this->renvoieSaisies->removeElement($renvoieSaisy)) {
            // set the owning side to null (unless already changed)
            if ($renvoieSaisy->getFomulaireId() === $this) {
                $renvoieSaisy->setFomulaireId(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->nom;
    }

    public function getName() {
        return $this->nom;
    }
}
