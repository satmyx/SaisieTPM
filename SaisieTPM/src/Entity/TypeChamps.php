<?php

namespace App\Entity;

use App\Repository\TypeChampsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeChampsRepository::class)]
class TypeChamps
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Typage = null;

    #[ORM\OneToMany(mappedBy: 'id_type', targetEntity: Champs::class, orphanRemoval: true)]
    private Collection $champs;

    public function __construct()
    {
        $this->champs = new ArrayCollection();
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

    public function getTypage(): ?string
    {
        return $this->Typage;
    }

    public function setTypage(string $Typage): self
    {
        $this->Typage = $Typage;

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
            $champ->setIdType($this);
        }

        return $this;
    }

    public function removeChamp(Champs $champ): self
    {
        if ($this->champs->removeElement($champ)) {
            // set the owning side to null (unless already changed)
            if ($champ->getIdType() === $this) {
                $champ->setIdType(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->nom;
    }
}
