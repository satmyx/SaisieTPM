<?php

namespace App\Entity;

use App\Repository\ChampsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChampsRepository::class)]
class Champs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'champs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeChamps $id_type = null;

    #[ORM\ManyToMany(targetEntity: Formulaire::class, inversedBy: 'champs')]
    private Collection $choisir;

    #[ORM\ManyToOne(inversedBy: 'champs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $utilisateur = null;

    public function __construct()
    {
        $this->choisir = new ArrayCollection();
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

    public function getIdType(): ?TypeChamps
    {
        return $this->id_type;
    }

    public function setIdType(?TypeChamps $id_type): self
    {
        $this->id_type = $id_type;

        return $this;
    }

    /**
     * @return Collection<int, Formulaire>
     */
    public function getChoisir(): Collection
    {
        return $this->choisir;
    }

    public function addChoisir(Formulaire $choisir): self
    {
        if (!$this->choisir->contains($choisir)) {
            $this->choisir->add($choisir);
        }

        return $this;
    }

    public function removeChoisir(Formulaire $choisir): self
    {
        $this->choisir->removeElement($choisir);

        return $this;
    }

    public function __toString() {
        return $this->nom;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
